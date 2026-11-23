(function () {
  const flash = window.AppFlash || {};

  if (window.toastr) {
    toastr.options = {
      closeButton: true,
      progressBar: true,
      positionClass: 'toast-top-right',
      timeOut: 3500,
    };

    if (flash.success) {
      toastr.success(flash.success);
    }

    if (flash.error) {
      toastr.error(flash.error);
    }
  }

  document.querySelectorAll('[data-live-clock]').forEach(function (clock) {
    const dateTarget = clock.querySelector('[data-live-date]');
    const timeTarget = clock.querySelector('[data-live-time]');
    const dateFormatter = new Intl.DateTimeFormat('id-ID', {
      weekday: 'long',
      day: '2-digit',
      month: 'long',
      year: 'numeric',
      timeZone: 'Asia/Jakarta',
    });
    const timeFormatter = new Intl.DateTimeFormat('id-ID', {
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
      hour12: false,
      timeZone: 'Asia/Jakarta',
    });

    function updateClock() {
      const now = new Date();

      if (dateTarget) {
        dateTarget.textContent = dateFormatter.format(now);
      }

      if (timeTarget) {
        timeTarget.textContent = timeFormatter.format(now).replace(/\./g, ':');
      }
    }

    updateClock();
    setInterval(updateClock, 1000);
  });

  document.addEventListener('click', function (event) {
    const trigger = event.target.closest('[data-confirm-delete]');

    if (!trigger || !window.Swal) {
      return;
    }

    event.preventDefault();

    Swal.fire({
      title: trigger.dataset.confirmTitle || 'Hapus data?',
      text: trigger.dataset.confirmText || 'Data yang sudah dihapus tidak dapat dikembalikan.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal',
    }).then(function (result) {
      if (result.isConfirmed) {
        window.location.href = trigger.href;
      }
    });
  });

  document.addEventListener('click', function (event) {
    const printTrigger = event.target.closest('[data-print-page]');
    if (printTrigger) {
      event.preventDefault();
      window.print();
    }
  });

  document.addEventListener('submit', function (event) {
    const form = event.target.closest('[data-confirm-submit]');

    if (!form || !window.Swal || form.dataset.confirmed === 'true') {
      return;
    }

    event.preventDefault();

    const conflictAlert = form.querySelector('[data-conflict-alert][data-block-submit="true"]:not(.d-none)');

    if (conflictAlert) {
      Swal.fire({
        title: 'Slot bentrok',
        text: conflictAlert.textContent.trim() || 'Pilih ruang, hari, atau jam lain sebelum menyimpan.',
        icon: 'warning',
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Cek lagi',
      });
      return;
    }

    Swal.fire({
      title: form.dataset.confirmTitle || 'Simpan data?',
      text: form.dataset.confirmText || 'Pastikan data yang diisi sudah benar.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#007bff',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, simpan',
      cancelButtonText: 'Batal',
    }).then(function (result) {
      if (result.isConfirmed) {
        form.dataset.confirmed = 'true';
        form.submit();
      }
    });
  });

  document.querySelectorAll('[data-slot-select]').forEach(function (select) {
    const form = select.closest('form');
    const codeInput = form ? form.querySelector('[data-slot-code]') : null;
    const startInput = form ? form.querySelector('[data-slot-start]') : null;
    const endInput = form ? form.querySelector('[data-slot-end]') : null;
    const preview = form ? form.querySelector('[data-slot-preview]') : null;

    function syncSlot() {
      const option = select.options[select.selectedIndex];

      if (!option) {
        return;
      }

      if (codeInput) {
        codeInput.value = option.dataset.code || option.value;
      }

      if (startInput) {
        startInput.value = option.dataset.start || '';
      }

      if (endInput) {
        endInput.value = option.dataset.end || '';
      }

      if (preview) {
        preview.textContent = (option.textContent || '').split('|').pop().trim();
      }
    }

    select.addEventListener('change', syncSlot);
    syncSlot();
  });

  document.querySelectorAll('[data-schedule-form]').forEach(function (form) {
    const grid = form.querySelector('[data-schedule-grid]');
    const roomSelect = form.querySelector('[data-room-select]');
    const daySelect = form.querySelector('[data-day-select]');
    const jamSelect = form.querySelector('[data-jam-select]');
    const classSelect = form.querySelector('[name="kelas_ids[]"]');
    const alertBox = form.querySelector('[data-conflict-alert]');

    if (!grid || !roomSelect || !daySelect || !jamSelect) {
      return;
    }

    let schedules = [];

    try {
      schedules = JSON.parse(grid.dataset.schedules || '[]');
    } catch (error) {
      schedules = [];
    }

    const editId = Number(grid.dataset.editId || 0);
    const cells = Array.from(grid.querySelectorAll('td[data-day][data-jam-id]'));

    function cellFor(day, jamId) {
      return cells.find(function (cell) {
        return cell.dataset.day === day && Number(cell.dataset.jamId) === Number(jamId);
      });
    }

    function emptyCell(cell) {
      cell.className = '';
      cell.innerHTML = '<span class="slot-empty"><i class="far fa-circle mr-1"></i>Kosong</span>';
    }

    function filledCell(cell, schedule) {
      const isCurrent = editId && Number(schedule.id) === editId;
      cell.classList.add(isCurrent ? 'is-current' : 'is-filled');
      cell.innerHTML = '';

      const wrapper = document.createElement('div');
      wrapper.className = 'slot-filled';

      const title = document.createElement('strong');
      title.textContent = schedule.mata_kuliah || '-';

      const meta = document.createElement('span');
      meta.textContent = [schedule.kelas, schedule.prodi].filter(Boolean).join(' - ');

      const icon = document.createElement('i');
      icon.className = isCurrent ? 'fas fa-pen mr-1' : 'fas fa-book mr-1';

      title.prepend(icon);
      wrapper.append(title, meta);

      if (schedule.dosen) {
        const lecturer = document.createElement('small');
        lecturer.textContent = schedule.dosen;
        wrapper.appendChild(lecturer);
      }

      cell.appendChild(wrapper);
    }

    function selectedClassIds() {
      if (!classSelect) {
        return [];
      }

      return Array.from(classSelect.selectedOptions).map(function (option) {
        return Number(option.value);
      }).filter(Boolean);
    }

    function scheduleClassIds(schedule) {
      if (Array.isArray(schedule.kelas_ids)) {
        return schedule.kelas_ids.map(Number).filter(Boolean);
      }

      return [];
    }

    function hasSharedClass(schedule, classIds) {
      const ids = scheduleClassIds(schedule);

      return ids.some(function (id) {
        return classIds.includes(id);
      });
    }

    function updateGrid() {
      const selectedRoomId = Number(roomSelect.value);
      const selectedDay = daySelect.value;
      const selectedJamId = Number(jamSelect.value);
      const classIds = selectedClassIds();
      let selectedRoomSchedule = null;
      let selectedClassSchedule = null;

      cells.forEach(emptyCell);

      schedules
        .filter(function (schedule) {
          return Number(schedule.ruang_id) === selectedRoomId;
        })
        .forEach(function (schedule) {
          const cell = cellFor(schedule.hari, schedule.jam_id);

          if (!cell) {
            return;
          }

          filledCell(cell, schedule);

          if (Number(schedule.id) !== editId && schedule.hari === selectedDay && Number(schedule.jam_id) === selectedJamId) {
            selectedRoomSchedule = selectedRoomSchedule || schedule;
          }
        });

      selectedClassSchedule = schedules.find(function (schedule) {
        return Number(schedule.id) !== editId
          && schedule.hari === selectedDay
          && Number(schedule.jam_id) === selectedJamId
          && hasSharedClass(schedule, classIds);
      }) || null;

      const selectedCell = cellFor(selectedDay, selectedJamId);

      if (selectedCell) {
        selectedCell.classList.add('is-selected');

        if (selectedClassSchedule) {
          selectedCell.classList.add('is-class-conflict');
        }
      }

      if (alertBox) {
        const hasClassConflict = Boolean(selectedClassSchedule);
        const hasRoomSchedule = Boolean(selectedRoomSchedule);
        alertBox.classList.toggle('d-none', !hasClassConflict && !hasRoomSchedule);
        alertBox.classList.toggle('alert-warning', hasClassConflict);
        alertBox.classList.toggle('alert-info', !hasClassConflict && hasRoomSchedule);
        alertBox.dataset.blockSubmit = hasClassConflict ? 'true' : 'false';

        if (hasClassConflict || hasRoomSchedule) {
          alertBox.textContent = '';

          const icon = document.createElement('i');
          icon.className = hasClassConflict ? 'fas fa-exclamation-triangle mr-1' : 'fas fa-info-circle mr-1';
          const schedule = selectedClassSchedule || selectedRoomSchedule;
          const message = hasClassConflict
            ? 'Kelas yang dipilih sudah punya jadwal ' + (schedule.mata_kuliah || 'lain') + ' (' + (schedule.kelas || '-') + ') pada jam ini.'
            : 'Slot ruangan ini sudah berisi ' + (schedule.mata_kuliah || 'jadwal lain') + ' (' + (schedule.kelas || '-') + '). Kelas gabungan tetap diperbolehkan jika memang satu unit jadwal.';

          alertBox.append(
            icon,
            document.createTextNode(message)
          );
        }
      }
    }

    [roomSelect, daySelect, jamSelect, classSelect].filter(Boolean).forEach(function (select) {
      select.addEventListener('change', updateGrid);
    });

    updateGrid();
  });

  document.querySelectorAll('[data-kelas-by-prodi]').forEach(function (select) {
    const prodiSelect = document.querySelector('[data-prodi-select]');
    const sourceOptions = Array.from(select.options).map(function (option) {
      return option.cloneNode(true);
    });

    function syncClassOptions() {
      const selectedProdiId = prodiSelect ? prodiSelect.value : '';
      const selectedValue = select.dataset.selected || select.value || '';

      select.innerHTML = '';

      sourceOptions.forEach(function (option) {
        const optionProdiId = option.dataset.prodiId || '';

        if (!option.value || !selectedProdiId || optionProdiId === selectedProdiId) {
          select.appendChild(option.cloneNode(true));
        }
      });

      if (selectedValue && Array.from(select.options).some(function (option) {
        return option.value === selectedValue;
      })) {
        select.value = selectedValue;
      } else {
        select.value = '';
      }

      select.dataset.selected = select.value;
    }

    if (prodiSelect) {
      prodiSelect.addEventListener('change', function () {
        select.dataset.selected = '';
        syncClassOptions();
      });
    }

    select.addEventListener('change', function () {
      select.dataset.selected = select.value;
    });

    syncClassOptions();
  });

  document.querySelectorAll('[data-print-page]').forEach(function (button) {
    button.addEventListener('click', function () {
      window.print();
    });
  });

  document.querySelectorAll('[data-dashboard-charts]').forEach(function (container) {
    if (!window.ApexCharts) {
      return;
    }

    let charts = {};

    try {
      charts = JSON.parse(container.dataset.dashboardCharts || '{}');
    } catch (error) {
      charts = {};
    }

    const dayChart = document.getElementById('scheduleByDayChart');
    const roomChart = document.getElementById('roomUsageChart');
    const chartColors = ['#2563eb', '#16a34a', '#f59e0b', '#dc2626', '#4f46e5', '#0891b2', '#be123c'];

    function integerValue(value) {
      return Number(value || 0).toLocaleString('id-ID');
    }

    if (dayChart && charts.byDay) {
      new ApexCharts(dayChart, {
        chart: {
          type: 'bar',
          height: 285,
          parentHeightOffset: 0,
          toolbar: { show: false },
          fontFamily: 'Source Sans Pro, Arial, sans-serif',
        },
        series: [{
          name: 'Jadwal aktif',
          data: charts.byDay.values || [],
        }],
        colors: chartColors,
        plotOptions: {
          bar: {
            borderRadius: 7,
            columnWidth: '52%',
            distributed: true,
            dataLabels: {
              position: 'top',
            },
          },
        },
        dataLabels: {
          enabled: true,
          style: {
            colors: ['#111827'],
            fontWeight: 700,
          },
          offsetY: -18,
          formatter: integerValue,
        },
        grid: {
          borderColor: '#e5e7eb',
          strokeDashArray: 4,
          padding: {
            left: 8,
            right: 12,
          },
        },
        legend: { show: false },
        xaxis: {
          categories: charts.byDay.labels || [],
          labels: {
            style: {
              colors: '#64748b',
              fontWeight: 700,
            },
          },
          axisBorder: { show: false },
          axisTicks: { show: false },
        },
        yaxis: {
          min: 0,
          forceNiceScale: true,
          labels: {
            formatter: integerValue,
            style: { colors: '#64748b' },
          },
        },
        tooltip: {
          theme: 'light',
          y: {
            formatter: function (value) {
              return integerValue(value) + ' jadwal';
            },
          },
        },
        states: {
          hover: { filter: { type: 'lighten', value: 0.08 } },
          active: { filter: { type: 'none' } },
        },
        noData: {
          text: 'Belum ada jadwal aktif',
          align: 'center',
          verticalAlign: 'middle',
          style: { color: '#64748b', fontSize: '14px' },
        },
        responsive: [{
          breakpoint: 576,
          options: {
            chart: { height: 245 },
            plotOptions: { bar: { columnWidth: '64%' } },
            dataLabels: { enabled: false },
          },
        }],
      }).render();
    }

    if (roomChart && charts.byRoom) {
      new ApexCharts(roomChart, {
        chart: {
          type: 'donut',
          height: 285,
          parentHeightOffset: 0,
          toolbar: { show: false },
          fontFamily: 'Source Sans Pro, Arial, sans-serif',
        },
        labels: charts.byRoom.labels || [],
        series: charts.byRoom.values || [],
        colors: chartColors,
        stroke: {
          width: 3,
          colors: ['#ffffff'],
        },
        dataLabels: {
          enabled: true,
          dropShadow: { enabled: false },
          style: {
            fontWeight: 700,
          },
        },
        legend: {
          position: 'bottom',
          fontSize: '13px',
          fontWeight: 700,
          labels: { colors: '#374151' },
          markers: {
            width: 10,
            height: 10,
            radius: 8,
          },
        },
        plotOptions: {
          pie: {
            expandOnClick: false,
            donut: {
              size: '64%',
              labels: {
                show: true,
                name: {
                  color: '#64748b',
                  fontSize: '13px',
                  fontWeight: 700,
                },
                value: {
                  color: '#111827',
                  fontSize: '28px',
                  fontWeight: 800,
                  formatter: integerValue,
                },
                total: {
                  show: true,
                  label: 'Total',
                  color: '#64748b',
                  formatter: function (chart) {
                    return integerValue(chart.globals.seriesTotals.reduce(function (total, value) {
                      return total + value;
                    }, 0));
                  },
                },
              },
            },
          },
        },
        tooltip: {
          theme: 'light',
          y: {
            formatter: function (value) {
              return integerValue(value) + ' jadwal';
            },
          },
        },
        noData: {
          text: 'Belum ada penggunaan ruang',
          align: 'center',
          verticalAlign: 'middle',
          style: { color: '#64748b', fontSize: '14px' },
        },
        responsive: [{
          breakpoint: 576,
          options: {
            chart: { height: 255 },
            legend: { fontSize: '12px' },
            dataLabels: { enabled: false },
          },
        }],
      }).render();
    }
  });
})();

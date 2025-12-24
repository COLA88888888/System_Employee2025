<template>
  <div class="container-fluid mt-4">
    <div class="row mb-4">
      <div class="col-md-12">
        <h1 class="h3 mb-3">ລາຍງານການເຮັດວຽກ</h1>
        
        <!-- Filters Card -->
        <div class="card mb-4">
          <div class="card-body">
            <div class="row g-2">
              <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">ວັນທີເລີ່ມ</label>
                <DatePickerLao v-model="startDate" @update:modelValue="GenerateReport" dateFormat="Y-m-d" placeholder="ເລືອກວັນທີ"></DatePickerLao>
              </div>
              <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">ວັນທີສິ້ນສຸດ</label>
                <DatePickerLao v-model="endDate" @update:modelValue="GenerateReport" dateFormat="Y-m-d" placeholder="ເລືອກວັນທີ"></DatePickerLao>
              </div>
              <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">ພະນັກງານ</label>
                <select class="form-control" v-model="selectedEmployee" @change="GenerateReport">
                  <option value="">ທັງໝົດ</option>
                  <option v-for="emp in employees" :key="emp.id" :value="emp.id">
                    {{ emp.fname }}
                  </option>
                </select>
              </div>
              <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">&nbsp;</label>
                <button class="btn btn-primary w-100" @click="GenerateReport">
                  <i class="bx bx-refresh"></i> ດຶງລາຍງານ
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4 g-2">
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card bg-primary text-white">
          <div class="card-body">
            <div class="text-muted text-white mb-2">ວັນເຮັດວຽກທັງໝົດ</div>
            <div class="h5">{{ summary.totalDays }} ວັນ</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card bg-success text-white">
          <div class="card-body">
            <div class="text-muted text-white mb-2">ວັນເຮັດວຽກເຕັມ</div>
            <div class="h5">{{ summary.fullDays }} ວັນ</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card bg-warning text-white">
          <div class="card-body">
            <div class="text-muted text-white mb-2">ວັນເຮັດວຽກບາງສ່ວນ</div>
            <div class="h5">{{ summary.partialDays }} ວັນ</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card bg-danger text-white">
          <div class="card-body">
            <div class="text-muted text-white mb-2">ວັນຂາດວຽກ</div>
            <div class="h5">{{ summary.absentDays }} ວັນ</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">ສະຖານະການເຮັດວຽກ</h6>
          </div>
          <div class="card-body" style="height: 300px;">
            <canvas ref="attendanceChartRef"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">ຈຳນວນຊົ່ວໂມງຕາມພະນັກງານ</h6>
          </div>
          <div class="card-body" style="height: 300px;">
            <canvas ref="hoursChartRef"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Detail Table -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">ລາຍລະອຽດການເຮັດວຽກ</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm table-striped">
                <thead>
                  <tr>
                    <th>ວັນທີ</th>
                    <th>ຊື່ພະນັກງານ</th>
                    <th class="text-center">ເວລາເຂົ້າ</th>
                    <th class="text-center">ເວລາອອກ</th>
                    <th class="text-center">ຊົ່ວໂມງ</th>
                    <th>ສະຖານະ</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-if="attendanceData && attendanceData.length">
                    <tr v-for="record in attendanceData" :key="record.id">
                      <td>{{ record.date_here }}</td>
                      <td>{{ record.employee_fname }}</td>
                      <td class="text-center">{{ record.check_in }}</td>
                      <td class="text-center">{{ record.check_out }}</td>
                      <td class="text-center">{{ record.hour }}</td>
                      <td>
                        <span v-if="record.hour >= 8" class="badge bg-success">ປົກກະຕິ</span>
                        <span v-else-if="record.hour > 0" class="badge bg-warning">ເຄິ່ງມື້</span>
                        <span v-else class="badge bg-danger">ຂາດ</span>
                      </td>
                    </tr>
                  </template>
                  <tr v-else>
                    <td colspan="6" class="text-center text-muted">ບໍ່ມີຂໍ້ມູນ</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Chart from 'chart.js/auto';
import { useStaff } from '../Staff/AuthLogin';
import DatePickerLao from '../Components/DatePickerLao.vue';

export default {
  name: 'AttendanceReport',
  components: {
    DatePickerLao
  },
  setup() {
    const staff = useStaff();
    return { staff };
  },
  data() {
    return {
      attendanceData: [],
      employees: [],
      summary: {
        totalDays: 0,
        fullDays: 0,
        partialDays: 0,
        absentDays: 0
      },
      startDate: new Date(new Date().setDate(new Date().getDate() - 30)).toISOString().split('T')[0],
      endDate: new Date().toISOString().split('T')[0],
      selectedEmployee: '',
      attendanceChart: null,
      hoursChart: null
    };
  },
  methods: {
    GenerateReport() {
      axios.get(`/api/attendance?start_date=${this.startDate}&end_date=${this.endDate}&perpage=all`, {
        headers: {
          Authorization: `Bearer ${this.staff.getToken}`
        }
      }).then(response => {
        let data = response.data.data || response.data;
        
        if (this.selectedEmployee) {
          data = data.filter(item => item.employee_id === parseInt(this.selectedEmployee));
        }

        this.attendanceData = data;
        this.calculateSummary();
        this.UpdateCharts();
      }).catch(error => {
        console.error('Error fetching attendance data:', error);
      });
    },

    calculateSummary() {
      let fullDays = 0;
      let partialDays = 0;
      let absentDays = 0;

      this.attendanceData.forEach(record => {
        const hours = parseFloat(record.hour) || 0;
        if (hours >= 8) fullDays++;
        else if (hours > 0) partialDays++;
        else absentDays++;
      });

      this.summary = {
        totalDays: this.attendanceData.length,
        fullDays,
        partialDays,
        absentDays
      };
    },

    UpdateCharts() {
      this.$nextTick(() => {
        this.updateAttendanceChart();
        this.updateHoursChart();
      });
    },

    updateAttendanceChart() {
      const ctx = this.$refs.attendanceChartRef;
      if (!ctx) return;

      if (this.attendanceChart) {
        this.attendanceChart.destroy();
      }

      const data = {
        labels: ['ເຕັມວັນ', 'ຄຣ່ຶ່ງວັນ', 'ຂາດວຽກ'],
        datasets: [{
          label: 'ຈຳນວນວັນ',
          data: [this.summary.fullDays, this.summary.partialDays, this.summary.absentDays],
          backgroundColor: [
            'rgba(75, 192, 75, 0.6)',
            'rgba(255, 193, 7, 0.6)',
            'rgba(244, 67, 54, 0.6)'
          ],
          borderWidth: 1
        }]
      };

      this.attendanceChart = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom'
            }
          }
        }
      });
    },

    updateHoursChart() {
      const ctx = this.$refs.hoursChartRef;
      if (!ctx) return;

      if (this.hoursChart) {
        this.hoursChart.destroy();
      }

      // Group by employee and sum hours
      const employeeHours = {};
      this.attendanceData.forEach(record => {
        if (!employeeHours[record.employee_fname]) {
          employeeHours[record.employee_fname] = 0;
        }
        employeeHours[record.employee_fname] += parseFloat(record.hour) || 0;
      });

      const labels = Object.keys(employeeHours);
      const data = Object.values(employeeHours);

      this.hoursChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'ຊົ່ວໂມງ',
            data: data,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          indexAxis: 'y',
          scales: {
            x: {
              beginAtZero: true
            }
          }
        }
      });
    },

    GetEmployees() {
      axios.get('/api/employee?perpage=all', {
        headers: {
          Authorization: `Bearer ${this.staff.getToken}`
        }
      }).then(response => {
        this.employees = response.data;
      }).catch(error => {
        console.error('Error fetching employees:', error);
      });
    }
  },

  mounted() {
    this.GetEmployees();
    this.GenerateReport();
  }
};
</script>

<style scoped>
.card {
  border-radius: 0.375rem;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
}

.text-muted {
  color: #6c757d;
  font-size: 0.9rem;
  font-weight: 500;
}

.table-responsive {
  max-height: 500px;
  overflow-y: auto;
}
</style>
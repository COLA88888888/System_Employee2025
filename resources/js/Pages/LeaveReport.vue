<template>
  <div class="container-fluid mt-4">
    <div class="row mb-4">
      <div class="col-md-12">
        <h1 class="h3 mb-3">ລາຍງານການລາພັກ</h1>
        
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
                <label class="form-label">ປະເພດການລາພັກ</label>
                <select class="form-control" v-model="selectedLeaveType" @change="GenerateReport">
                  <option value="">ທັງໝົດ</option>
                  <option v-for="type in leaveTypes" :key="type.id" :value="type.id">
                    {{ type.name }}
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
            <div class="text-muted text-white mb-2">ລວມຄຳຮ້ອງຂໍ</div>
            <div class="h5">{{ summary.totalRequests }} ໃບ</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card bg-success text-white">
          <div class="card-body">
            <div class="text-muted text-white mb-2">ອະນຸມັດແລ້ວ</div>
            <div class="h5">{{ summary.approved }} ໃບ</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card bg-warning text-white">
          <div class="card-body">
            <div class="text-muted text-white mb-2">ລໍຖ້າອະນຸມັດ</div>
            <div class="h5">{{ summary.pending }} ໃບ</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card bg-danger text-white">
          <div class="card-body">
            <div class="text-muted text-white mb-2">ປະຕິເສດ</div>
            <div class="h5">{{ summary.rejected }} ໃບ</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">ສະຖານະຄຳຮ້ອງຂໍ</h6>
          </div>
          <div class="card-body" style="height: 300px;">
            <canvas ref="statusChartRef"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">ລາຍລະອຽດຕາມປະເພດ</h6>
          </div>
          <div class="card-body" style="height: 300px;">
            <canvas ref="typeChartRef"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Summary Tables -->
    <div class="row mb-4 g-2">
      <div class="col-12 col-md-6">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">ສະຫຼຸບຕາມພະນັກງານ</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm table-striped">
                <thead>
                  <tr>
                    <th>ຊື່ພະນັກງານ</th>
                    <th class="text-center">ວັນລາ</th>
                    <th class="text-center">ຈຳນວນໃບ</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-if="Object.keys(summary.byEmployee || {}).length">
                    <tr v-for="(emp, name) in summary.byEmployee" :key="name">
                      <td>{{ name }}</td>
                      <td class="text-center">{{ emp.days }}</td>
                      <td class="text-center">{{ emp.count }}</td>
                    </tr>
                  </template>
                  <tr v-else>
                    <td colspan="3" class="text-center text-muted">ບໍ່ມີຂໍ້ມູນ</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">ສະຫຼຸບຕາມປະເພດ</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm table-striped">
                <thead>
                  <tr>
                    <th>ປະເພດ</th>
                    <th class="text-center">ວັນລາ</th>
                    <th class="text-center">ຈຳນວນໃບ</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-if="Object.keys(summary.byType || {}).length">
                    <tr v-for="(type, name) in summary.byType" :key="name">
                      <td>{{ name }}</td>
                      <td class="text-center">{{ type.days }}</td>
                      <td class="text-center">{{ type.count }}</td>
                    </tr>
                  </template>
                  <tr v-else>
                    <td colspan="3" class="text-center text-muted">ບໍ່ມີຂໍ້ມູນ</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Detail Table -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">ລາຍລະອຽດຄຳຮ້ອງຂໍ</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm table-striped">
                <thead>
                  <tr>
                    <th>ຊື່ພະນັກງານ</th>
                    <th>ປະເພດ</th>
                    <th>ວັນທີເລີ່ມ</th>
                    <th>ວັນທີສິ້ນສຸດ</th>
                    <th class="text-center">ວັນລາ</th>
                    <th>ສະຖານະ</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-if="leaveData && leaveData.length">
                    <tr v-for="record in leaveData" :key="record.id">
                      <td>{{ record.employee_fname }}</td>
                      <td>{{ record.break_type_name }}</td>
                      <td>{{ record.start_date }}</td>
                      <td>{{ record.end_date }}</td>
                      <td class="text-center">{{ calculateDays(record.start_date, record.end_date) }}</td>
                      <td>
                        <span v-if="record.status === 'ອະນຸມັດແລ້ວ'" class="badge bg-success">ອະນຸມັດ</span>
                        <span v-else-if="record.status === 'ລໍຖ້າອະນຸມັດ'" class="badge bg-warning">ລໍຖ້າ</span>
                        <span v-else class="badge bg-danger">ປະຕິເສດ</span>
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
  name: 'LeaveReport',
  components: {
    DatePickerLao
  },
  setup() {
    const staff = useStaff();
    return { staff };
  },
  data() {
    return {
      leaveData: [],
      leaveTypes: [],
      summary: {
        totalRequests: 0,
        approved: 0,
        pending: 0,
        rejected: 0,
        byEmployee: {},
        byType: {}
      },
      startDate: new Date(new Date().setMonth(new Date().getMonth() - 1)).toISOString().split('T')[0],
      endDate: new Date().toISOString().split('T')[0],
      selectedLeaveType: '',
      statusChart: null,
      typeChart: null
    };
  },
  methods: {
    GenerateReport() {
      axios.get(`/api/leave?start_date=${this.startDate}&end_date=${this.endDate}&perpage=all`, {
        headers: {
          Authorization: `Bearer ${this.staff.getToken}`
        }
      }).then(response => {
        let data = response.data.data || response.data;
        
        if (this.selectedLeaveType) {
          data = data.filter(item => item.break_type_id === parseInt(this.selectedLeaveType));
        }

        this.leaveData = data;
        this.calculateSummary();
        this.UpdateCharts();
      }).catch(error => {
        console.error('Error fetching leave data:', error);
      });
    },

    calculateSummary() {
      let approved = 0;
      let pending = 0;
      let rejected = 0;
      const byEmployee = {};
      const byType = {};

      this.leaveData.forEach(record => {
        // Count by status
        if (record.status === 'ອະນຸມັດແລ້ວ') approved++;
        else if (record.status === 'ລໍຖ້າອະນຸມັດ') pending++;
        else rejected++;

        // By employee
        if (!byEmployee[record.employee_fname]) {
          byEmployee[record.employee_fname] = { days: 0, count: 0 };
        }
        const days = this.calculateDays(record.start_date, record.end_date);
        byEmployee[record.employee_fname].days += days;
        byEmployee[record.employee_fname].count += 1;

        // By type
        if (!byType[record.break_type_name]) {
          byType[record.break_type_name] = { days: 0, count: 0 };
        }
        byType[record.break_type_name].days += days;
        byType[record.break_type_name].count += 1;
      });

      this.summary = {
        totalRequests: this.leaveData.length,
        approved,
        pending,
        rejected,
        byEmployee,
        byType
      };
    },

    calculateDays(startDate, endDate) {
      const start = new Date(startDate);
      const end = new Date(endDate);
      const diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
      return diff > 0 ? diff : 0;
    },

    UpdateCharts() {
      this.$nextTick(() => {
        this.updateStatusChart();
        this.updateTypeChart();
      });
    },

    updateStatusChart() {
      const ctx = this.$refs.statusChartRef;
      if (!ctx) return;

      if (this.statusChart) {
        this.statusChart.destroy();
      }

      const data = {
        labels: ['ອະນຸມັດ', 'ລໍຖ້າ', 'ປະຕິເສດ'],
        datasets: [{
          label: 'ຈຳນວນໃບ',
          data: [this.summary.approved, this.summary.pending, this.summary.rejected],
          backgroundColor: [
            'rgba(75, 192, 75, 0.6)',
            'rgba(255, 193, 7, 0.6)',
            'rgba(244, 67, 54, 0.6)'
          ],
          borderWidth: 1
        }]
      };

      this.statusChart = new Chart(ctx, {
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

    updateTypeChart() {
      const ctx = this.$refs.typeChartRef;
      if (!ctx) return;

      if (this.typeChart) {
        this.typeChart.destroy();
      }

      const types = Object.keys(this.summary.byType);
      const days = types.map(t => this.summary.byType[t].days);

      this.typeChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: types,
          datasets: [{
            label: 'ວັນລາ',
            data: days,
            backgroundColor: 'rgba(156, 39, 176, 0.6)',
            borderColor: 'rgba(156, 39, 176, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    },

    GetLeaveTypes() {
      axios.get('/api/break-type?perpage=all', {
        headers: {
          Authorization: `Bearer ${this.staff.getToken}`
        }
      }).then(response => {
        this.leaveTypes = response.data;
      }).catch(error => {
        console.error('Error fetching leave types:', error);
      });
    }
  },

  mounted() {
    this.GetLeaveTypes();
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
  max-height: 400px;
  overflow-y: auto;
}
</style>
<template>
  <div class="container-fluid mt-4">
    <div class="row mb-4">
      <div class="col-md-12">
        <h1 class="h3 mb-3">ລາຍງານພະນັກງານ</h1>
        
        <!-- Filters Card -->
        <div class="card mb-4">
          <div class="card-body">
            <div class="row g-2">
              <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">ພະແນກ</label>
                <select class="form-control" v-model="selectedDepartment" @change="GenerateReport">
                  <option value="">ທັງໝົດ</option>
                  <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                    {{ dept.name }}
                  </option>
                </select>
              </div>
              <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">ຕຳແໜ່ງ</label>
                <select class="form-control" v-model="selectedPosition" @change="GenerateReport">
                  <option value="">ທັງໝົດ</option>
                  <option v-for="pos in positions" :key="pos.id" :value="pos.id">
                    {{ pos.pos_name }}
                  </option>
                </select>
              </div>
              <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">ສະຖານະ</label>
                <select class="form-control" v-model="selectedStatus" @change="GenerateReport">
                  <option value="">ທັງໝົດ</option>
                  <option value="ໃຊ້ງານຢູ່">ໃຊ້ງານຢູ່</option>
                  <option value="ອອກຈາກວຽກ">ອອກຈາກວຽກ</option>
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
      <div class="col-12 col-sm-6 col-md-4">
        <div class="card bg-primary text-white">
          <div class="card-body">
            <div class="text-muted text-white mb-2">ລວມພະນັກງານ</div>
            <div class="h5">{{ summary.total }} ຄົນ</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4">
        <div class="card bg-success text-white">
          <div class="card-body">
            <div class="text-muted text-white mb-2">ໃຊ້ງານຢູ່</div>
            <div class="h5">{{ summary.active }} ຄົນ</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4">
        <div class="card bg-danger text-white">
          <div class="card-body">
            <div class="text-muted text-white mb-2">ອອກຈາກວຽກ</div>
            <div class="h5">{{ summary.inactive }} ຄົນ</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">ຈຳນວນພະນັກງານຕາມພະແນກ</h6>
          </div>
          <div class="card-body" style="height: 300px;">
            <canvas ref="departmentChartRef"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">ຈຳນວນພະນັກງານຕາມຕຳແໜ່ງ</h6>
          </div>
          <div class="card-body" style="height: 300px;">
            <canvas ref="positionChartRef"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Summary Tables -->
    <div class="row mb-4 g-2">
      <div class="col-12 col-md-6">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">ສະຫຼຸບຕາມພະແນກ</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm table-striped">
                <thead>
                  <tr>
                    <th>ພະແນກ</th>
                    <th class="text-center">ຈຳນວນ</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-if="Object.keys(summary.byDepartment || {}).length">
                    <tr v-for="(count, dept) in summary.byDepartment" :key="dept">
                      <td>{{ dept }}</td>
                      <td class="text-center">{{ count }}</td>
                    </tr>
                  </template>
                  <tr v-else>
                    <td colspan="2" class="text-center text-muted">ບໍ່ມີຂໍ້ມູນ</td>
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
            <h6 class="mb-0">ສະຫຼຸບຕາມຕຳແໜ່ງ</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm table-striped">
                <thead>
                  <tr>
                    <th>ຕຳແໜ່ງ</th>
                    <th class="text-center">ຈຳນວນ</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-if="Object.keys(summary.byPosition || {}).length">
                    <tr v-for="(count, pos) in summary.byPosition" :key="pos">
                      <td>{{ pos }}</td>
                      <td class="text-center">{{ count }}</td>
                    </tr>
                  </template>
                  <tr v-else>
                    <td colspan="2" class="text-center text-muted">ບໍ່ມີຂໍ້ມູນ</td>
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
            <h6 class="mb-0">ລາຍລະອຽດພະນັກງານ</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm table-striped">
                <thead>
                  <tr>
                    <th>ລະຫັດ</th>
                    <th>ຊື່ພະນັກງານ</th>
                    <th>ພະແນກ</th>
                    <th>ຕຳແໜ່ງ</th>
                    <th>ເບີໂທລະສັບ</th>
                    <th>ອີເມວ</th>
                    <th>ສະຖານະ</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-if="employeeData && employeeData.length">
                    <tr v-for="emp in employeeData" :key="emp.id">
                      <td>{{ emp.id }}</td>
                      <td>{{ emp.fname }}</td>
                      <td>{{ emp.department_name }}</td>
                      <td>{{ emp.position_name }}</td>
                      <td>{{ emp.phone }}</td>
                      <td>{{ emp.email }}</td>
                      <td>
                        <span v-if="emp.status === 'ໃຊ້ງານຢູ່'" class="badge bg-success">ໃຊ້ງານ</span>
                        <span v-else class="badge bg-danger">ອອກຈາກວຽກ</span>
                      </td>
                    </tr>
                  </template>
                  <tr v-else>
                    <td colspan="7" class="text-center text-muted">ບໍ່ມີຂໍ້ມູນ</td>
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

export default {
  name: 'EmployeeReport',
  setup() {
    const staff = useStaff();
    return { staff };
  },
  data() {
    return {
      employeeData: [],
      departments: [],
      positions: [],
      summary: {
        total: 0,
        active: 0,
        inactive: 0,
        byDepartment: {},
        byPosition: {}
      },
      selectedDepartment: '',
      selectedPosition: '',
      selectedStatus: '',
      departmentChart: null,
      positionChart: null
    };
  },
  methods: {
    GenerateReport() {
      let params = '?perpage=all';
      if (this.selectedDepartment) params += `&department_id=${this.selectedDepartment}`;
      if (this.selectedPosition) params += `&position_id=${this.selectedPosition}`;
      if (this.selectedStatus) params += `&status=${this.selectedStatus}`;

      axios.get(`/api/employee${params}`, {
        headers: {
          Authorization: `Bearer ${this.staff.getToken}`
        }
      }).then(response => {
        this.employeeData = response.data;
        this.calculateSummary();
        this.UpdateCharts();
      }).catch(error => {
        console.error('Error fetching employee data:', error);
      });
    },

    calculateSummary() {
      let active = 0;
      let inactive = 0;
      const byDepartment = {};
      const byPosition = {};

      this.employeeData.forEach(emp => {
        // Count by status
        if (emp.status === 'ໃຊ້ງານຢູ່') active++;
        else inactive++;

        // By department
        if (!byDepartment[emp.department_name]) {
          byDepartment[emp.department_name] = 0;
        }
        byDepartment[emp.department_name]++;

        // By position
        if (!byPosition[emp.position_name]) {
          byPosition[emp.position_name] = 0;
        }
        byPosition[emp.position_name]++;
      });

      this.summary = {
        total: this.employeeData.length,
        active,
        inactive,
        byDepartment,
        byPosition
      };
    },

    UpdateCharts() {
      this.$nextTick(() => {
        this.updateDepartmentChart();
        this.updatePositionChart();
      });
    },

    updateDepartmentChart() {
      const ctx = this.$refs.departmentChartRef;
      if (!ctx) return;

      if (this.departmentChart) {
        this.departmentChart.destroy();
      }

      const depts = Object.keys(this.summary.byDepartment);
      const counts = Object.values(this.summary.byDepartment);

      this.departmentChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: depts,
          datasets: [{
            label: 'ຈຳນວນພະນັກງານ',
            data: counts,
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
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

    updatePositionChart() {
      const ctx = this.$refs.positionChartRef;
      if (!ctx) return;

      if (this.positionChart) {
        this.positionChart.destroy();
      }

      const positions = Object.keys(this.summary.byPosition);
      const counts = Object.values(this.summary.byPosition);

      this.positionChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: positions,
          datasets: [{
            label: 'ຈຳນວນ',
            data: counts,
            backgroundColor: [
              'rgba(255, 99, 132, 0.6)',
              'rgba(54, 162, 235, 0.6)',
              'rgba(255, 206, 86, 0.6)',
              'rgba(75, 192, 192, 0.6)',
              'rgba(153, 102, 255, 0.6)',
              'rgba(255, 159, 64, 0.6)',
              'rgba(199, 199, 199, 0.6)',
              'rgba(83, 102, 255, 0.6)'
            ],
            borderWidth: 1
          }]
        },
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

    GetDepartments() {
      axios.get('/api/department?perpage=all', {
        headers: {
          Authorization: `Bearer ${this.staff.getToken}`
        }
      }).then(response => {
        this.departments = response.data;
      }).catch(error => {
        console.error('Error fetching departments:', error);
      });
    },

    GetPositions() {
      axios.get('/api/position?perpage=all', {
        headers: {
          Authorization: `Bearer ${this.staff.getToken}`
        }
      }).then(response => {
        this.positions = response.data;
      }).catch(error => {
        console.error('Error fetching positions:', error);
      });
    }
  },

  mounted() {
    this.GetDepartments();
    this.GetPositions();
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
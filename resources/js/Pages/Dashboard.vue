<template>
  <div class="dashboard-container">
    <!-- Quick Stats Cards -->
    <div class="row mb-4">
      <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
        <div class="card stat-card h-100 border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
              <div class="avatar-box bg-label-primary rounded p-2 me-3">
                <i class="bx bx-group fs-3"></i>
              </div>
              <h6 class="card-title mb-0 text-muted">ພະນັກງານທັງໝົດ</h6>
            </div>
            <div class="d-flex align-items-end">
              <h3 class="mb-0 fw-bold">{{ stats.employees }}</h3>
              <span class="ms-2 text-success small mb-1"><i class="bx bx-up-arrow-alt"></i> ຄົນ</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
        <div class="card stat-card h-100 border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
              <div class="avatar-box bg-label-success rounded p-2 me-3">
                <i class="bx bx-check-double fs-3"></i>
              </div>
              <h6 class="card-title mb-0 text-muted">ເຂົ້າວຽກມື້ນີ້</h6>
            </div>
            <div class="d-flex align-items-end">
              <h3 class="mb-0 fw-bold">{{ stats.attendance }}</h3>
              <span class="ms-2 text-success small mb-1">ຄົນ</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
        <div class="card stat-card h-100 border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
              <div class="avatar-box bg-label-warning rounded p-2 me-3">
                <i class="bx bx-dollar fs-3"></i>
              </div>
              <h6 class="card-title mb-0 text-muted">ເງິນເດືອນເດືອນນີ້</h6>
            </div>
            <div class="d-flex align-items-end">
              <h3 class="mb-0 fw-bold">{{ formatCurrency(stats.payroll) }}</h3>
              <span class="ms-1 text-muted small mb-1">₭</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-0">
        <div class="card stat-card h-100 border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
              <div class="avatar-box bg-label-danger rounded p-2 me-3">
                <i class="bx bx-calendar-exclamation fs-3"></i>
              </div>
              <h6 class="card-title mb-0 text-muted">ລາພັກລໍຖ້າອະນຸມັດ</h6>
            </div>
            <div class="d-flex align-items-end">
              <h3 class="mb-0 fw-bold">{{ stats.leaves }}</h3>
              <span class="ms-2 text-danger small mb-1">ລາຍການ</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
      <!-- Main Trend Chart -->
      <div class="col-lg-8 mb-4">
        <div class="card h-100 border-0 shadow-sm">
          <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0 fw-bold">ແນວໂນ້ມການຈ່າຍເງິນເດືອນ (6 ເດືອນຫຼ້າສຸດ)</h5>
          </div>
          <div class="card-body">
            <canvas id="payrollTrendChart" height="300"></canvas>
          </div>
        </div>
      </div>
      
      <!-- Department Pie Chart -->
      <div class="col-lg-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
          <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0 fw-bold">ພະນັກງານແຍກຕາມຜະແນກ</h5>
          </div>
          <div class="card-body d-flex flex-column align-items-center justify-content-center">
            <div style="width: 100%; max-width: 250px;">
              <canvas id="deptChart"></canvas>
            </div>
            <div class="mt-4 w-100">
              <div v-for="(dept, index) in deptStats" :key="index" class="d-flex justify-content-between align-items-center mb-2 px-3">
                <div class="d-flex align-items-center">
                  <span class="dot me-2" :style="{ backgroundColor: chartColors[index % chartColors.length] }"></span>
                  <span class="text-muted small">{{ dept.name }}</span>
                </div>
                <span class="fw-bold small">{{ dept.count }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Attendance Trend Chart -->
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0 fw-bold">ສະຖິຕິການເຂົ້າວຽກ (7 ວັນຫຼ້າສຸດ)</h5>
          </div>
          <div class="card-body">
            <canvas id="attendanceTrendChart" height="150"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useStaff } from '../Staff/AuthLogin';
import axios from 'axios';
import Chart from 'chart.js/auto';

export default {
  name: "Dashboard",
  setup() {
    const staff = useStaff();
    return { staff };
  },
  data() {
    return {
      stats: {
        employees: 0,
        attendance: 0,
        payroll: 0,
        leaves: 0
      },
      deptStats: [],
      payrollTrend: [],
      attendanceTrend: [],
      chartColors: ['#696cff', '#03c3ec', '#71dd37', '#ffab00', '#ff3e1d', '#8592a3', '#00d25b', '#ff4646'],
      charts: {}
    };
  },
  methods: {
    fetchDashboardData() {
      if (!this.staff.getToken) return;
      
      axios.get('/api/dashboard/stats', {
        headers: { Authorization: 'Bearer ' + this.staff.getToken }
      }).then(res => {
        this.stats = res.data.stats;
        this.deptStats = res.data.deptStats;
        this.payrollTrend = res.data.payrollTrend;
        this.attendanceTrend = res.data.attendanceTrend;
        
        // Use nextTick to ensure canvas elements are available before rendering charts
        this.$nextTick(() => {
          this.initPayrollChart();
          this.initDeptChart();
          this.initAttendanceChart();
        });
      }).catch(err => {
        console.error("Dashboard error:", err);
      });
    },
    initPayrollChart() {
      const ctx = document.getElementById('payrollTrendChart');
      if (!ctx) return;
      
      if (this.charts.payroll) this.charts.payroll.destroy();
      
      this.charts.payroll = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: this.payrollTrend.map(t => t.month),
          datasets: [{
            label: 'ຍອດລວມເງິນເດືອນ (₭)',
            data: this.payrollTrend.map(t => t.amount),
            backgroundColor: 'rgba(105, 108, 255, 0.8)',
            borderColor: '#696cff',
            borderWidth: 0,
            borderRadius: 8,
            barThickness: 30,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: { display: true, color: '#f0f2f5' },
              ticks: { 
                callback: function(value) { return value.toLocaleString(); }
              }
            },
            x: { grid: { display: false } }
          }
        }
      });
    },
    initDeptChart() {
      const ctx = document.getElementById('deptChart');
      if (!ctx) return;
      
      if (this.charts.dept) this.charts.dept.destroy();
      
      this.charts.dept = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: this.deptStats.map(d => d.name),
          datasets: [{
            data: this.deptStats.map(d => d.count),
            backgroundColor: this.chartColors,
            hoverOffset: 4,
            borderWidth: 2,
            borderColor: '#fff'
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false }
          },
          cutout: '70%',
          maintainAspectRatio: false
        }
      });
    },
    initAttendanceChart() {
      const ctx = document.getElementById('attendanceTrendChart');
      if (!ctx) return;
      
      if (this.charts.attendance) this.charts.attendance.destroy();
      
      this.charts.attendance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: this.attendanceTrend.map(t => t.date),
          datasets: [{
            label: 'ຈຳນວນຄົນເຂົ້າວຽກ',
            data: this.attendanceTrend.map(t => t.count),
            fill: true,
            backgroundColor: 'rgba(113, 221, 55, 0.1)',
            borderColor: '#71dd37',
            tension: 0.4,
            pointBackgroundColor: '#71dd37',
            pointBorderColor: '#fff',
            pointHoverRadius: 6,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: { color: '#f0f2f5' },
              ticks: { stepSize: 1 }
            },
            x: { grid: { display: false } }
          }
        }
      });
    },
    formatCurrency(value) {
      if (!value) return '0';
      return value.toLocaleString();
    }
  },
  mounted() {
    this.fetchDashboardData();
  }
};
</script>

<style scoped>
.dashboard-container {
  padding: 10px;
  animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.welcome-card {
  background: linear-gradient(135deg, #ffffff 0%, #f4f5ff 100%);
}

.welcome-img {
  width: 180px;
  bottom: -20px;
  right: 20px;
}

.stat-card {
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.bg-label-primary { background-color: #e7e7ff !important; color: #696cff !important; }
.bg-label-success { background-color: #e8fadf !important; color: #71dd37 !important; }
.bg-label-warning { background-color: #fff2d6 !important; color: #ffab00 !important; }
.bg-label-danger { background-color: #ffe5e5 !important; color: #ff3e1d !important; }

.dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  display: inline-block;
}

.card-title {
  font-size: 1rem;
}

h3 {
  font-size: 1.75rem;
}

.card-header {
  border-bottom: 1px solid #f0f2f5 !important;
}

@media (max-width: 991px) {
  .welcome-img {
    display: none;
  }
}
</style>
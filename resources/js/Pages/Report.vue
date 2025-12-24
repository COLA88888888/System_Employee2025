<template>
  <div class="container-fluid mt-4">
    <div class="row mb-4">
      <div class="col-md-12">
        <h4 class="h3 mb-3 fw-bold">{{$t('Salary transfer')}}</h4>
      </div>
    </div>

    <!-- Filters Card -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="row g-2">
          <div class="col-12 col-sm-6 col-md-3">
            <label class="form-label">{{$t('select_month')}}</label>
            <DatePickerLao v-model="payroll.selectedMonth" @update:modelValue="GeneratePayrollReport" dateFormat="Y-m" monthPicker placeholder="ເລືອກເດືອນປີ"></DatePickerLao>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <label class="form-label">{{$t('select_department')}}</label>
            <select class="form-control" v-model="payroll.selectedDepartment" @change="GeneratePayrollReport">
              <option value="">{{$t('all_departments')}}</option>
              <option v-for="dept in departments" :key="dept.id" :value="dept.name">
                {{ dept.name }}
              </option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4 g-2">
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card bg-primary text-white">
          <div class="card-body">
            <div class="text-muted text-white mb-2">{{$t('total_salary')}}</div>
            <div class="h5">{{ formatCurrency(payroll.summary.totalSalary) }} ກີບ</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card bg-info text-white">
          <div class="card-body">
            <div class="text-muted text-white mb-2">{{$t('total_bonus')}}</div>
            <div class="h5">{{ formatCurrency(payroll.summary.totalBonus) }} ກີບ</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card bg-warning  text-white">
          <div class="card-body">
            <div class="text-muted text-white mb-2">{{$t('total_deduction')}}</div>
            <div class="h5">{{ formatCurrency(payroll.summary.totalDeduction) }} ກີບ</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card bg-success text-white">
          <div class="card-body">
            <div class="text-muted text-white mb-2">{{$t('total_amount')}}</div>
            <div class="h5">{{ formatCurrency(payroll.summary.totalAmount) }} {{ $t('kip') }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4 g-2">
      <div class="col-md-4">
        <div class="card">
          <div class="card-header"><h6 class="mb-0">  {{$t('report_department')}}</h6></div>
          <div class="card-body" style="height: 300px;">
            <canvas ref="payrollDeptChartRef"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-header"><h6 class="mb-0">{{$t('report_position')}}</h6></div>
          <div class="card-body" style="height: 300px;">
            <canvas ref="payrollPosChartRef"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-header"><h6 class="mb-0">{{$t('report_trend')}}</h6></div>
          <div class="card-body" style="height: 300px;">
            <canvas ref="payrollTrendChartRef"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Summary Tables -->
    <!-- <div class="row mb-4 g-2">
      <div class="col-12 col-md-6">
        <div class="card">
          <div class="card-header"><h6 class="mb-0">ສະຫຼຸບເງິນເດືອນຕາມພະແນກ</h6></div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm table-striped">
                <thead>
                  <tr>
                    <th>ພະແນກ</th>
                    <th class="text-center">ຈຳນວນ</th>
                    <th class="text-center">ລວມເງິນ</th>
                    <th class="text-center">ສະເລ່ຍ</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-if="Object.keys(payroll.summary.byDepartment || {}).length">
                    <tr v-for="(dept, name) in payroll.summary.byDepartment" :key="name">
                      <td>{{ name }}</td>
                      <td class="text-center">{{ dept.count }}</td>
                      <td class="text-center">{{ formatCurrency(dept.amount) }}</td>
                      <td class="text-center">{{ formatCurrency(dept.amount / dept.count) }}</td>
                    </tr>
                  </template>
                  <tr v-else>
                    <td colspan="4" class="text-center text-muted">ບໍ່ມີຂໍ້ມູນ</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="card">
          <div class="card-header"><h6 class="mb-0">ສະຫຼຸບເງິນເດືອນຕາມຕຳແໜ່ງ</h6></div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm table-striped">
                <thead>
                  <tr>
                    <th>ຕຳແໜ່ງ</th>
                    <th class="text-center">ຈຳນວນ</th>
                    <th class="text-center">ລວມເງິນ</th>
                    <th class="text-center">ສະເລ່ຍ</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-if="Object.keys(payroll.summary.byPosition || {}).length">
                    <tr v-for="(pos, name) in payroll.summary.byPosition" :key="name">
                      <td>{{ name }}</td>
                      <td class="text-center">{{ pos.count }}</td>
                      <td class="text-center">{{ formatCurrency(pos.amount) }}</td>
                      <td class="text-center">{{ formatCurrency(pos.amount / pos.count) }}</td>
                    </tr>
                  </template>
                  <tr v-else>
                    <td colspan="4" class="text-center text-muted">ບໍ່ມີຂໍ້ມູນ</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div> -->
  </div>
</template>

<script>
import axios from 'axios';
import Chart from 'chart.js/auto';
import { useStaff } from '../Staff/AuthLogin';
import DatePickerLao from '../Components/DatePickerLao.vue';

export default {
  name: 'PayrollReport',
  components: {
    DatePickerLao
  },
  setup() {
    const staff = useStaff();
    return { staff };
  },
  data() {
    return {
      departments: [],
      payroll: {
        selectedMonth: new Date().toISOString().slice(0, 7),
        selectedDepartment: '',
        data: [],
        summary: {
          totalSalary: 0,
          totalBonus: 0,
          totalDeduction: 0,
          totalAmount: 0,
          byDepartment: {},
          byPosition: {}
        },
        charts: { dept: null, pos: null, trend: null }
      }
    };
  },
  
  methods: {
    formatCurrency(value) {
      if (value === null || value === undefined || value === '') return '0';
      const num = this.cleanNumber(value);
      return num.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    },

    cleanNumber(value) {
      if (!value) return 0;
      const stringValue = String(value);
      const cleaned = stringValue.replace(/[^\d.]/g, '');
      return parseFloat(cleaned) || 0;
    },

    // ===== PAYROLL =====
    GeneratePayrollReport() {
      const [year, month] = this.payroll.selectedMonth.split('-');
      const searchMonth = `${year}-${month}`;

      axios.get(`/api/payroll?pay_month=${searchMonth}&perpage=all`, {
        headers: { Authorization: `Bearer ${this.staff.getToken}` }
      }).then(response => {
        let data = response.data.data || response.data;
        if (this.payroll.selectedDepartment) {
          data = data.filter(item => item.department_name === this.payroll.selectedDepartment);
        }
        this.payroll.data = data;
        this.calculatePayrollSummary();
        this.UpdatePayrollCharts();
      }).catch(error => console.error('Error:', error));
    },

    calculatePayrollSummary() {
      let totalSalary = 0, totalBonus = 0, totalDeduction = 0, totalAmount = 0;
      const byDepartment = {}, byPosition = {};

      this.payroll.data.forEach(item => {
        const salary = parseFloat(item.salary) || 0;
        const bonus = parseFloat(item.bonus) || 0;
        const deduction = parseFloat(item.del_salary) || 0;
        const amount = parseFloat(item.amount) || 0;

        totalSalary += salary;
        totalBonus += bonus;
        totalDeduction += deduction;
        totalAmount += amount;

        if (!byDepartment[item.department_name]) {
          byDepartment[item.department_name] = { salary: 0, bonus: 0, deduction: 0, amount: 0, count: 0 };
        }
        byDepartment[item.department_name].salary += salary;
        byDepartment[item.department_name].bonus += bonus;
        byDepartment[item.department_name].deduction += deduction;
        byDepartment[item.department_name].amount += amount;
        byDepartment[item.department_name].count += 1;

        if (!byPosition[item.position_name]) {
          byPosition[item.position_name] = { salary: 0, bonus: 0, deduction: 0, amount: 0, count: 0 };
        }
        byPosition[item.position_name].salary += salary;
        byPosition[item.position_name].bonus += bonus;
        byPosition[item.position_name].deduction += deduction;
        byPosition[item.position_name].amount += amount;
        byPosition[item.position_name].count += 1;
      });

      this.payroll.summary = { totalSalary, totalBonus, totalDeduction, totalAmount, byDepartment, byPosition };
    },

    UpdatePayrollCharts() {
      this.$nextTick(() => {
        this.updatePayrollDeptChart();
        this.updatePayrollPosChart();
        this.updatePayrollTrendChart();
      });
    },

    updatePayrollDeptChart() {
      const ctx = this.$refs.payrollDeptChartRef;
      if (!ctx) return;
      if (this.payroll.charts.dept) this.payroll.charts.dept.destroy();

      const depts = Object.keys(this.payroll.summary.byDepartment);
      const salaries = depts.map(d => this.payroll.summary.byDepartment[d].salary);
      const bonuses = depts.map(d => this.payroll.summary.byDepartment[d].bonus);

      this.payroll.charts.dept = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: depts,
          datasets: [
            { label: 'ເງິນເດືອນ', data: salaries, backgroundColor: 'rgba(75, 192, 192, 0.6)', borderWidth: 1 },
            { label: 'ໂບນັດ', data: bonuses, backgroundColor: 'rgba(54, 162, 235, 0.6)', borderWidth: 1 }
          ]
        },
        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
      });
    },

    updatePayrollPosChart() {
      const ctx = this.$refs.payrollPosChartRef;
      if (!ctx) return;
      if (this.payroll.charts.pos) this.payroll.charts.pos.destroy();

      const positions = Object.keys(this.payroll.summary.byPosition);
      const amounts = positions.map(p => this.payroll.summary.byPosition[p].amount);

      this.payroll.charts.pos = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: positions,
          datasets: [{
            label: 'ລວມເງິນ',
            data: amounts,
            backgroundColor: ['rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)', 'rgba(75, 192, 192, 0.6)', 'rgba(153, 102, 255, 0.6)'],
            borderWidth: 1
          }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
      });
    },

    updatePayrollTrendChart() {
      const ctx = this.$refs.payrollTrendChartRef;
      if (!ctx) return;
      if (this.payroll.charts.trend) this.payroll.charts.trend.destroy();

      const byMonth = {};
      this.payroll.data.forEach(item => {
        const month = item.pay_month || 'Unknown';
        if (!byMonth[month]) byMonth[month] = { total: 0, count: 0 };
        byMonth[month].total += parseFloat(item.amount) || 0;
        byMonth[month].count += 1;
      });

      const months = Object.keys(byMonth).sort();
      const averages = months.map(m => byMonth[m].total / byMonth[m].count);

      this.payroll.charts.trend = new Chart(ctx, {
        type: 'line',
        data: {
          labels: months,
          datasets: [{
            label: 'ສະເລ່ຍເງິນ',
            data: averages,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            fill: true,
            tension: 0.4
          }]
        },
        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
      });
    },

    ExportPayrollExcel() {
      axios.get('/api/payroll/export', {
        headers: { Authorization: `Bearer ${this.staff.getToken}` },
        responseType: 'blob'
      }).then(response => {
        const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'ລາຍງານເງິນເດືອນ.xlsx');
        document.body.appendChild(link);
        link.click();
        link.remove();
      }).catch(error => console.error('Error exporting:', error));
    },

    // ===== INIT =====
    LoadData() {
      axios.get('/api/department?perpage=all', {
        headers: { Authorization: `Bearer ${this.staff.getToken}` }
      }).then(response => { this.departments = response.data; }).catch(error => console.error('Error:', error));
    }
  },

  mounted() {
    this.LoadData();
    this.GeneratePayrollReport();
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
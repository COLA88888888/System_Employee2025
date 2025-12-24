/**
 * Payroll Report Utilities
 * Calculate bonuses, salaries, and generate report statistics
 */

export function calculateBonusSummary(payrollData) {
  if (!payrollData || payrollData.length === 0) {
    return {
      totalSalary: 0,
      totalBonus: 0,
      totalDeduction: 0,
      totalAmount: 0,
      averageSalary: 0,
      averageBonus: 0,
      byDepartment: {},
      byPosition: {}
    };
  }

  let totalSalary = 0;
  let totalBonus = 0;
  let totalDeduction = 0;
  let totalAmount = 0;
  const byDepartment = {};
  const byPosition = {};

  payrollData.forEach(item => {
    const salary = parseFloat(item.salary) || 0;
    const bonus = parseFloat(item.bonus) || 0;
    const deduction = parseFloat(item.del_salary) || 0;
    const amount = parseFloat(item.amount) || 0;

    totalSalary += salary;
    totalBonus += bonus;
    totalDeduction += deduction;
    totalAmount += amount;

    // Group by department
    if (!byDepartment[item.department_name]) {
      byDepartment[item.department_name] = {
        salary: 0,
        bonus: 0,
        deduction: 0,
        amount: 0,
        count: 0
      };
    }
    byDepartment[item.department_name].salary += salary;
    byDepartment[item.department_name].bonus += bonus;
    byDepartment[item.department_name].deduction += deduction;
    byDepartment[item.department_name].amount += amount;
    byDepartment[item.department_name].count += 1;

    // Group by position
    if (!byPosition[item.position_name]) {
      byPosition[item.position_name] = {
        salary: 0,
        bonus: 0,
        deduction: 0,
        amount: 0,
        count: 0
      };
    }
    byPosition[item.position_name].salary += salary;
    byPosition[item.position_name].bonus += bonus;
    byPosition[item.position_name].deduction += deduction;
    byPosition[item.position_name].amount += amount;
    byPosition[item.position_name].count += 1;
  });

  const averageSalary = payrollData.length > 0 ? totalSalary / payrollData.length : 0;
  const averageBonus = payrollData.length > 0 ? totalBonus / payrollData.length : 0;

  return {
    totalSalary,
    totalBonus,
    totalDeduction,
    totalAmount,
    averageSalary,
    averageBonus,
    count: payrollData.length,
    byDepartment,
    byPosition
  };
}

export function prepareDepartmentChartData(summary) {
  const departments = Object.keys(summary.byDepartment);
  const salaries = departments.map(d => summary.byDepartment[d].salary);
  const bonuses = departments.map(d => summary.byDepartment[d].bonus);
  const amounts = departments.map(d => summary.byDepartment[d].amount);

  return {
    labels: departments,
    datasets: [
      {
        label: 'ເງິນເດືອນ',
        data: salaries,
        backgroundColor: 'rgba(75, 192, 192, 0.6)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      },
      {
        label: 'ໂບນັດ',
        data: bonuses,
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }
    ]
  };
}

export function preparePositionChartData(summary) {
  const positions = Object.keys(summary.byPosition);
  const amounts = positions.map(p => summary.byPosition[p].amount);

  return {
    labels: positions,
    datasets: [
      {
        label: 'ລວມເງິນ',
        data: amounts,
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
      }
    ]
  };
}

export function prepareSalaryTrendData(payrollData) {
  if (!payrollData || payrollData.length === 0) {
    return { labels: [], datasets: [] };
  }

  // Group by pay_month
  const byMonth = {};
  payrollData.forEach(item => {
    const month = item.pay_month || 'Unknown';
    if (!byMonth[month]) {
      byMonth[month] = { total: 0, count: 0 };
    }
    byMonth[month].total += parseFloat(item.amount) || 0;
    byMonth[month].count += 1;
  });

  const months = Object.keys(byMonth).sort();
  const averages = months.map(m => byMonth[m].total / byMonth[m].count);

  return {
    labels: months,
    datasets: [
      {
        label: 'ສະເລ່ຍເງິນຈ່າຍໃນເດືອນ',
        data: averages,
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.1)',
        fill: true,
        tension: 0.4
      }
    ]
  };
}

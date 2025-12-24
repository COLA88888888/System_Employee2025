/**
 * Payroll Print Utilities
 * Extracted print functions for cleaner Vue component
 */

/**
 * Format currency with thousand separators
 * @param {number|string} value - The value to format
 * @returns {string} - Formatted number
 */
function formatCurrency(value) {
  if (value === null || value === undefined || value === '') return '0';
  const num = cleanNumber(value);
  return num.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
}

/**
 * Clean numeric string (remove non-numeric characters except decimal)
 * @param {number|string} value - The value to clean
 * @returns {number} - Cleaned number
 */
function cleanNumber(value) {
  if (!value) return 0;
  const stringValue = String(value);
  const cleaned = stringValue.replace(/[^\d.]/g, '');
  return parseFloat(cleaned) || 0;
}

/**
 * Print a single payslip
 * @param {Object} items - Payroll item object
 */
export function printSingle(items) {
  const printContent = `
  <html>
  <head>
    <style>
      body {
        font-family: 'Saysettha OT', sans-serif;
        padding: 25px;
        background: #f5f5f5;
      }
      .slip-container {
        width: 700px;
        margin: auto;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px #ccc;
        border: 1px solid #ddd;
      }
      .company-header {
        text-align: center;
        padding: 10px;
        border-bottom: 2px solid #333;
        margin-bottom: 15px;
      }
      .company-logo {
        height: 50px;
        margin-bottom: 10px;
      }
      .company-name {
        margin: 5px 0;
        font-size: 18px;
        font-weight: bold;
      }
      h2 {
        text-align: center;
        margin-bottom: 20px;
      }
      table {
        width: 100%;
        border-collapse: collapse;
        font-size: 18px;
      }
      td {
        padding: 8px 5px;
      }
      .label {
        width: 35%;
        font-weight: bold;
      }
      .amount {
        text-align: right;
      }
    </style>
  </head>
  <body>
    <div class="slip-container">
      <div class="company-header">
        <div class="company-logo">🏢</div>
        <div class="company-name">ບໍລິສັດ/ອົງການ</div>
      </div>
      <h2>ໃບບິນຈ່າຍເງິນເດືອນ</h2>
      <table>
        <tr><td class="label">ຊື່ພະນັກງານ:</td><td>${items.employee_fname}</td></tr>
        <tr><td class="label">ພະແນກ:</td><td>${items.department_name}</td></tr>
        <tr><td class="label">ຕຳແໜ່ງ:</td><td>${items.position_name}</td></tr>
        <tr><td class="label">ວັນທີຈ່າຍ:</td><td>${items.pay_month}</td></tr>
        <tr><td class="label">ເງິນເດືອນ:</td><td class="amount">${formatCurrency(items.salary)} ກີບ</td></tr>
        <tr><td class="label">ເງິນໂບນັດ:</td><td class="amount">${formatCurrency(items.bonus)} ກີບ</td></tr>
        <tr><td class="label">ຫັກເງິນ:</td><td class="amount">${formatCurrency(items.del_salary)} ກີບ</td></tr>
        <tr><td class="label">ລວມທັງໝົດ:</td><td class="amount"><b>${formatCurrency(items.amount)} ກີບ</b></td></tr>
      </table>
    </div>
  </body>
  </html>
  `;

  const w = window.open("", "_blank", "width=800,height=600");
  w.document.write(printContent);
  w.document.close();
  w.print();
}

/**
 * Print all payrolls in the current page
 * @param {Array} payrollData - Array of payroll items
 */
export function printAllPayrolls(payrollData) {
  const rows = (payrollData || []).map(it => `
    <tr>
      <td>${it.employee_fname}</td>
      <td>${it.department_name}</td>
      <td>${it.position_name}</td>
      <td>${it.pay_month}</td>
      <td class="num">${formatCurrency(it.salary)}</td>
      <td class="num">${formatCurrency(it.bonus)}</td>
      <td class="num">${formatCurrency(it.del_salary)}</td>
      <td class="num"><b>${formatCurrency(it.amount)}</b></td>
    </tr>
  `).join('');

  const content = `
  <html>
  <head>
    <style>
      body {
        font-family: 'Saysettha OT';
        padding: 25px;
        background: #f7f7f7;
      }
      .company-header {
        text-align: center;
        padding: 10px;
        border-bottom: 2px solid #333;
        margin-bottom: 15px;
      }
      .company-logo {
        height: 50px;
        margin-bottom: 10px;
        font-size: 40px;
      }
      .company-name {
        margin: 5px 0;
        font-size: 18px;
        font-weight: bold;
      }
      h2 {
        text-align: center;
        margin-bottom: 20px;
      }
      table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
      }
      th, td {
        border: 1px solid #bbb;
        padding: 8px;
      }
      th {
        background: #e5e5e5;
      }
      .num {
        text-align: right;
      }
    </style>
  </head>
  <body>
    <div class="company-header">
      <div class="company-logo">🏢</div>
      <div class="company-name">ບໍລິສັດ/ອົງການ</div>
    </div>
    <h2>ລາຍງານການຈ່າຍເງິນເດືອນພະນັກງານ</h2>
    <table>
      <thead>
        <tr>
          <th>ຊື່</th>
          <th>ພະແນກ</th>
          <th>ຕຳແໜ່ງ</th>
          <th>ວັນທີຈ່າຍ</th>
          <th>ເງິນເດືອນ</th>
          <th>ໂບນັດ</th>
          <th>ຫັກເງິນ</th>
          <th>ລວມ</th>
        </tr>
      </thead>
      <tbody>
        ${rows}
      </tbody>
    </table>
  </body>
  </html>
  `;

  const w = window.open('', '_blank', 'width=1000,height=800');
  w.document.write(content);
  w.document.close();
  w.print();
}

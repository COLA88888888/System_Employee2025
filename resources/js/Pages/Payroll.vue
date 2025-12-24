<template>
  <div class="card">
    <h5 class="card-header fw-bold">{{$t('payroll_report')}}</h5>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            
          </div>

          <div class="col-md-6 mb-2 d-flex justify-content-end">
        
            <div class="input-group w-auto me-2">
              <input type="text" class="form-control" v-model="Search"  :placeholder="$t('search_employee')" @keyup.enter="GetPayroll()"/>
              <button class="btn btn-primary" type="button" id="button-addon2" @click="GetPayroll()"><i class='bx bx-search-alt-2' ></i></button>
            </div>
            <button class="btn btn-success" type="button" @click="AddPayroll()">
              <span class="icon-base bx bx-plus icon-md"></span>{{$t('add')}}
            </button>
          </div>
        </div>

        <div class="row g-2 align-items-end flex-wrap mb-2">
     
            <select class="form-select w-auto" v-model="Perpage" @change="GetPayroll()">
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="30">30</option>
            </select>

          <div class="col-md-auto w-auto">
            <DatePickerLao  v-model="pay_month" :label="$t('pay_month') + ' :'" dateFormat="Y-m-d"  inputId="pay_month"/>
          </div>

          <div class="col-md-auto">
            <button class="btn btn-primary px-4 mt-md-0" @click="SearchData()">
              <i class='bx bx-search-alt-2'></i> {{$t('search')}}
            </button>
          </div>

          <div class="col-md-auto">
            <select class="form-select w-auto" v-model="SelectStatus" @change="GetPayroll()">
              <option value="all">{{$t('all_statuses')}}</option>
              <option value="ຈ່າຍແລ້ວ">{{$t('paid')}}</option>
              <option value="ຍັງບໍ່ທັນຈ່າຍ">{{$t('not_paid_yet')}}</option>
            </select>          
          </div>    

          <div class="col-auto ms-auto">
            <button class="btn btn-success me-2 px-4" @click="ExportPayroll()">
              <i class='bx bx-file me-1 fs-6'></i> {{$t('export')}}
            </button>

            <button class="btn btn-primary" @click="printAllPayslips()">
              <i class="bx bxs-printer fs-6 m-1"></i> {{$t('print')}}
            </button>
          </div>
        </div>

        <div class="row">
          <div class="table-responsive text-nowrap">
            <table class="table">
              <thead class="table-light">
                <tr>
                  <th>{{$t('id')}}</th>
                  <th>{{$t('employee')}}</th>
                  <th>{{$t('department')}}</th>
                  <th>{{$t('position')}}</th>
                  <th>{{$t('pay_month')}}</th>
                  <th>{{$t('salary')}}</th>
                  <th>{{$t('bonus')}}</th>
                  <th>{{$t('deduction')}}</th>
                  <th>{{$t('total_amount')}}</th>
                  <th>{{$t('payment_method')}}</th>
                  <th>{{$t('status')}}</th>
                  <th>{{$t('actions')}}</th>
                </tr>
              </thead>
              <tbody v-if="PayrollData.data.length > 0" class="table-border-bottom-0">
                <tr v-for="items in PayrollData.data" :key="items.id">
                  <td>{{items.id}}</td>
                  <td>{{items.employee_fname}}</td>
                  <td>{{items.department_name}}</td>
                  <td>{{items.position_name}}</td>
                  <td>{{ $formatDate(items.pay_month) }}</td>
                  <td>{{ formatCurrency(items.salary)}} {{$t('kip')}}</td>
                  <td>{{ formatCurrency(items.bonus)}} {{$t('kip')}}</td>
                  <td>{{ formatCurrency(items.del_salary)}} {{$t('kip')}}</td>
                  <td>{{ formatCurrency(items.amount)}} {{$t('kip')}}</td>
                  <td>{{items.pay_method}}</td>
                  <td>
                    <span :class="{'fw-bold text-success': items.status === 'ຈ່າຍແລ້ວ' , 'fw-bold text-danger': items.status === 'ຍັງບໍ່ທັນຈ່າຍ',}">
                      <i v-if="items.status === 'ຈ່າຍແລ້ວ'" class="bx bx-check-circle text-success fs-5 me-1"></i>
                      <i v-if="items.status === 'ຍັງບໍ່ທັນຈ່າຍ'" class="bx bx-x-circle text-danger fs-5 me-1"></i>
                      {{ items.status }}
                    </span>
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      <i v-if="items.status === 'ຈ່າຍແລ້ວ'"  class="bx bx-check-circle text-success fs-4 me-2" title="ຈ່າຍແລ້ວ"></i>

                      <div v-if="items.status !== 'ຈ່າຍແລ້ວ'" class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>

                        <div class="dropdown-menu">
                          <button class="dropdown-item d-flex align-items-center text-warning" @click="UpdateStatus(items.id, 'ຈ່າຍແລ້ວ')">
                            <i class="bx bx-money fs-5 me-2"></i> {{$t('paid')}}
                          </button>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr>
                  <td colspan="12" class="text-center">
                      <i class="bx bxs-data fs-4"></i> {{$t('no_data')}}
                    </td>
                </tr>
              </tbody>
            </table>
            <Pagination :pagination="PayrollData" :offset="4" @paginate="GetPayroll($event)" />
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="FormPayroll" data-bs-backdrop="static" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="backDropModalTitle">{{$t('form_payroll')}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-4">
                <label class="form-label fw-bold">{{$t('employee')}}: <span class="text-danger">*</span></label>
                <select class="form-select" v-model="FormPayroll.employee_id" @change="SelectEmployee">
                  <option value="">{{$t('select_employee')}}</option>
                  <option v-for="employee in EmployeeData" :key="employee.id" :value="employee.id">{{employee.fname}}</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label class="form-label fw-bold">{{$t('department')}}: <span class="text-danger">*</span></label>
                <input type="text" id="department_id" v-model="FormPayroll.department_name" class="form-control" :placeholder="$t('enter_department')" readonly/>
              </div>
            </div>   
          </div>  

          <div class="row">
            <div class="col-md-6">
              <div class="mb-4">            
                <label class="form-label fw-bold">{{$t('position')}}: <span class="text-danger">*</span></label>
                <input type="text" id="position" v-model="FormPayroll.position_name" class="form-control" :placeholder="$t('enter_position')" readonly />
              </div>
            </div> 

            <div class="col-md-6">
              <div class="mb-4">
                <label class="form-label fw-bold">{{$t('salary')}}: <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <cleave  :options="options" id="salary" v-model="FormPayroll.salary" @input="CalcTotalDebounced" class="form-control" :placeholder="$t('enter_salary')" />
                    <span class="input-group-text" id="basic-addon11">{{$t('kip')}}</span>
                  </div>
              </div>
            </div> 
          </div>     

          <div class="row">
            <div class="col-md-6">
              <div class="mb-4">
                <DatePickerLao v-model="FormPayroll.pay_month" :label="$t('pay_month') + ' :'" dateFormat="d-m-Y" inputId="start_date_form"/>
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-4">
                <label for="bonus" class="form-label fw-bold">{{$t('bonus')}}: <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <cleave  :options="options" id="bonus" v-model="FormPayroll.bonus" @input="CalcTotalDebounced" class="form-control" :placeholder="$t('enter_bonus')" />
                    <span class="input-group-text" id="basic-addon11">{{$t('kip')}}</span>
                  </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-4">
                <label for="del_salary" class="form-label fw-bold">{{$t('deduction')}}: <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <cleave  :options="options" id="del_salary" v-model="FormPayroll.del_salary" @input="CalcTotalDebounced" class="form-control" :placeholder="$t('enter_deducted')" />
                    <span class="input-group-text" id="basic-addon11">{{$t('kip')}}</span>
                  </div>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="mb-4">
                <label for="amount" class="form-label fw-bold">{{$t('total_amount')}}: <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="text" id="amount" :value="formatCurrency(FormPayroll.amount)" class="form-control" readonly />
                    <span class="input-group-text" id="basic-addon11">{{$t('kip')}}</span>
                  </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-4">
                <label class="form-label fw-bold" for="basic-default-country">{{$t('payment_method')}}: <span class="text-danger">*</span></label>
                <select class="form-select" v-model="FormPayroll.pay_method">
                  <option value="">{{$t('select_payment_method')}}...</option>
                  <option value="ຈ່າຍຜ່ານທະນາຄານ">{{$t('pay_via_bank')}}</option>
                  <option value="ຈ່າຍເປັນເງິນສົດ">{{$t('pay_in_cash')}}</option>
                </select>
              </div>
            </div>  

            <div class="col-md-6">
              <div class="mb-4">
                <label class="form-label fw-bold" for="basic-default-country">{{$t('status')}}: <span class="text-danger">*</span></label>
                <select class="form-select" v-model="FormPayroll.status">
                  <option value="ຍັງບໍ່ທັນຈ່າຍ">{{$t('not_paid_yet')}}</option>
                  <option value="ຈ່າຍແລ້ວ">{{$t('paid')}}</option>
                </select>
              </div>
            </div> 

          </div>
        </div> 
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" :disabled="IsFormPayroll" @click="SavePayroll()">{{$t('save')}}</button>
          <button type="button" class="btn btn-label-secondary border" data-bs-dismiss="modal">{{$t('close')}}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {useStaff} from '../Staff/AuthLogin.js';
import DatePickerLao from '../Components/DatePickerLao.vue';
import { printSingle, printAllPayrolls } from '../utils/payrollPrint.js';
import _ from 'lodash';

export default {

   components: {
    DatePickerLao,
  },
  setup() {
    const staff = useStaff();
    return {staff};
  },

  data() {
    return {
      Search:'',
      pay_month:'',
      Perpage:10,
      FormType: true,
      EditID: "",
      PayrollData:{
        data:[],
      },
      EmployeeData:[],
      DepartmentData:[],
      PositionData:[],
      SalaryData:[],
      SelectStatus: 'all',
      FormPayroll: {
        employee_id:'',
        department_id:'',
        position_id:'',
        pay_month:'',
        salary:'',
        bonus:'0',
        del_salary:'0',
        amount:'0',
        pay_method:'',
        status:'ຍັງບໍ່ທັນຈ່າຍ',
      },

      options: {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand',
        numeralDecimalMark: '.',
        delimiter: ',',
        numeralPositiveOnly: true,
        numeralIntegerScale: 15,
        numeralDecimalScale: 2, // Allow up to 2 decimal places
        stripLeadingZeroes: true // Remove leading zeroes
      }

    }
  },

  computed: {
    IsFormPayroll() {
      if(this.FormPayroll.employee_id === '' ||
        this.FormPayroll.pay_month === '' ||
        this.FormPayroll.salary === '' || 
        this.FormPayroll.pay_method === '' ||
        this.FormPayroll.status === ''
      ){
        return true;
      }
      else {
        return false;
      }
    }
  },

  methods: {

    // Call imported print utility for single payslip
    printSinglePayslip(items) {
      printSingle(items);
    },

    // Call imported print utility for all payrolls
    printAllPayslips() {
      printAllPayrolls(this.PayrollData.data || []);
    },

    // ເມື່ອເລືອກພະນັກງານ
    SelectEmployee() {
      const emp = this.EmployeeData.find(
        (e) => e.id == this.FormPayroll.employee_id
      );

      if (emp) {
        this.FormPayroll.department_name = emp.department_name;
        this.FormPayroll.position_name = emp.position_name;
        
        // Find the position to get the correct salary
        const position = this.PositionData.find(
          (p) => p.id == emp.position_id
        );
        
        if (position) {
          this.FormPayroll.salary = this.cleanNumber(position.salary);
        }

        // Set bonus and deduction to empty strings
        this.FormPayroll.salary = '';
        this.FormPayroll.bonus = 0;
        this.FormPayroll.del_salary = 0;

        // Calculate total
        this.CalcTotal();
      }
    },

    formatCurrency(value) {
      if (value === null || value === undefined || value === '') return '0';
      const num = this.cleanNumber(value);
      return num.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
    },

    AddPayroll() {
      this.FormType = true;
      this.EditID = '';
      $('#FormPayroll').modal('show');

      //clear form
      this.FormPayroll = {
        employee_id:'',
        department_id:'',
        position_id:'',
        pay_month:'',
        salary:'',
        bonus:'0',
        del_salary:'0',
        amount:'',
        pay_method:'',
        status:'ຍັງບໍ່ທັນຈ່າຍ',
      }
    },

    SavePayroll() {
      if(this.FormType) {
        axios.post('/api/payroll/add', this.FormPayroll, 
          {
            headers: {
                Authorization: 'Bearer ' + this.staff.getToken,
            },
          }
          ).then((response) => {
            if(response.data.success) {
              $("#FormPayroll").modal("hide");
               this.$swal({
                  position: "top-end", 
                  icon: "success", 
                  title: response.data.message, 
                  showConfirmButton: false, 
                  timer: 2500, 
                  toast: true, 
              });
              this.GetPayroll();
            }
            else {
              $('#FormPosition').modal('hide');
              this.$swal({
                icon: "error",
                text: response.data.message,
                title: "ຜິດພາດ",
                showConfirmButton: false, 
                // timer: 3500, 
              });
            }
          }).catch((error) => {
            if (error && error.response && error.response.status === 401) {
              this.$swal({
                title: "Token ໝົດອາຍຸແລ້ວ",
                text: "ກະລຸນາເຂົ້າລະບົບໃໝ່ !",
                icon: "error",
              }).then(() => {
                console.log("Token expired, redirecting to login page.");
                this.staff.logOut();
                localStorage.removeItem("web_token");
                localStorage.removeItem("web_user");
                this.$router.push("/login");
              });
            }
          });
      }
      else {
        // // Create a copy of the form data and clean all numeric values
        // const cleanData = {
        //   ...this.FormPayroll,
        //   salary: this.cleanNumber(this.FormPayroll.salary),
        //   bonus: this.cleanNumber(this.FormPayroll.bonus),
        //   del_salary: this.cleanNumber(this.FormPayroll.del_salary),
        //   amount: this.cleanNumber(this.FormPayroll.amount)
        // };

        //update payroll
        axios.post("/api/payroll/update/" + this.EditID, cleanData, {
          headers: {
            Authorization: "Bearer " + this.staff.getToken,
          },
        }).then((response) => {
            if (response.data.success) {
              this.$swal({
                position: "top-end",
                icon: "success",
                title: response.data.message,
                showConfirmButton: false,
                timer: 2500,
                toast: true,
              });
              $("#FormPayroll").modal("hide");
              this.FormType = true;
              this.EditID = '';
              this.GetPayroll(); // refresh product list
            } else {
              this.$swal({
                icon: "error",
                title: "ຜິດພາດ",
                text: response.data.message,
                showConfirmButton: false,
                timer: 3500,
              });
              this.GetPayroll();
            }
          })
          .catch((error) => {
            if (error && error.response && error.response.status === 401) {
              this.$swal({
                title: "Token ໝົດອາຍຸແລ້ວ",
                text: "ກະລຸນາເຂົ້າລະບົບໃໝ່ !",
                icon: "error",
              }).then(() => {
                console.log("Token expired, redirecting to login page.");
                this.staff.logOut();
                localStorage.removeItem("web_token");
                localStorage.removeItem("web_user");
                this.$router.push("/login");
              });
            }
          });
      }
    },

    ExportPayroll() {
      axios.get('/api/payroll/export', {
          headers: {
            Authorization: `Bearer ${this.staff.getToken}`
          },
          responseType: 'blob'  
      }).then(response => {
          const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
          const url = window.URL.createObjectURL(blob);

          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ເງິນເດືອນພະນັກງານ.xlsx');
          document.body.appendChild(link);
          link.click();
          link.remove();
      }).catch(error => {
          console.log(error);

          if(error && error.response && error.response.status === 401) {
            this.staff.logOut();
            localStorage.removeItem("web_token");
            localStorage.removeItem("web_user");
            this.$router.push("/login");
          }
      });
    },

    SearchData() {
      // Format the date properly for the API
      let searchParams = [];
      
      if (this.Search) {
        searchParams.push(`search=${encodeURIComponent(this.Search)}`);
      }
      
      // Handle date search - convert from d-m-Y to Y-m-d format if needed
      if (this.pay_month) {
        let formattedDate = this.pay_month;
        // If the date is in d-m-Y format, convert it to Y-m-d
        if (this.pay_month.match(/^\d{2}-\d{2}-\d{4}$/)) {
          const parts = this.pay_month.split('-');
          formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
        }
        searchParams.push(`pay_month=${encodeURIComponent(formattedDate)}`);
      }
      
      // Always include pagination and status parameters
      searchParams.push(`perpage=${this.Perpage}`);
      searchParams.push(`status=${this.SelectStatus}`);
      
      const queryString = searchParams.join('&');
      
      axios
        .get(`/api/payroll?${queryString}`, {
          headers: {
            Authorization: "Bearer " + this.staff.getToken,
          },
        })
        .then((response) => {
          this.PayrollData = response.data;
        })
        .catch((error) => {
          if (error && error.response && error.response.status === 401) {
            this.$swal({
              title: "Token ໝົດອາຍຸແລ້ວ",
              text: "ກະລຸນາເຂົ້າລະບົບໃໝ່ !",
              icon: "error",
            }).then(() => {
              this.staff.logOut();
              localStorage.removeItem("web_token");
              localStorage.removeItem("web_user");
              this.$router.push("/login");
            });
          }
        });
    },

    UpdateStatus(id, status) {
      this.$swal({
        title: "ຢືນຢັນການປ່ຽນສະຖານະ",
        text: `ຕ້ອງການປ່ຽນເປັນ "${status}" ຫຼືບໍ່?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "ຢືນຢັນ",
        cancelButtonText: "ຍົກເລີກ"
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post(`/api/payroll/status/${id}`, { status: status }, {
            headers: {
              Authorization: "Bearer " + this.staff.getToken,
            },
          })
          .then((res) => {
            if (res.data.success) {
              this.$swal({
                icon: "success",
                title: res.data.message,
                showConfirmButton: false,
                timer: 2000,
              });
              this.GetPayroll(); // ດຶງຂໍ້ມູນໃໝ່ຫຼັງຈາກອະນຸມັດ
            }
          })
          .catch((error) => {
            console.log(error);
          });
        }
      });
    },

    GetPayroll(page = 1) {
      // Build query parameters
      let params = [];
      params.push(`page=${page}`);
      
      if (this.SelectStatus && this.SelectStatus !== 'all') {
        params.push(`status=${this.SelectStatus}`);
      }
      
      if (this.Perpage) {
        params.push(`perpage=${this.Perpage}`);
      }
      
      if (this.Search) {
        params.push(`search=${encodeURIComponent(this.Search)}`);
      }
      
      // Handle date search - convert from d-m-Y to Y-m-d format if needed
      if (this.pay_month) {
        let formattedDate = this.pay_month;
        // If the date is in d-m-Y format, convert it to Y-m-d
        if (this.pay_month.match(/^\d{2}-\d{2}-\d{4}$/)) {
          const parts = this.pay_month.split('-');
          formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
        }
        params.push(`pay_month=${encodeURIComponent(formattedDate)}`);
      }
      
      const queryString = params.join('&');
      
      axios.get(`/api/payroll?${queryString}`, {
        headers: {
          Authorization: "Bearer " + this.staff.getToken,
        },
      }).then((response) => {
          this.PayrollData = response.data;
      }).catch((error) => {
        if (error && error.response && error.response.status === 401) {
          this.$swal({
            title: "Token ໝົດອາຍຸແລ້ວ",
            text: "ກະລຸນາເຂົ້າລະບົບໃໝ່ !",
            icon: "error",
          }).then(() => {
            console.log("Token expired, redirecting to login page.");
              this.staff.logOut();
              localStorage.removeItem("web_token");
              localStorage.removeItem("web_user");
              this.$router.push("/login");
            });
          }
        });
    },

    GetEmployee() {
        axios.get("/api/employee?perpage=all", {
          headers: {
            Authorization: "Bearer " + this.staff.getToken,
          },
        })
        .then((response) => {
          this.EmployeeData = response.data;
        })
        .catch((error) => {
          if (error && error.response && error.response.status === 401) { 
            this.staff.logOut();
            localStorage.removeItem("web_token");
            localStorage.removeItem("web_user");
            this.$router.push("/login");
          }
        });
    },

    GetDepartment() {
      axios.get("/api/department?perpage=all", {
        headers: {
          Authorization: "Bearer " + this.staff.getToken,
        },
      })
      .then((response) => {
        this.DepartmentData = response.data;
      })
      .catch((error) => {
        if (error && error.response && error.response.status === 401) { 
          this.staff.logOut();
          localStorage.removeItem("web_token");
          localStorage.removeItem("web_user");
          this.$router.push("/login");
        }
      });
    },

    GetPosition() {
      axios.get("/api/position?perpage=all", {
        headers: {
          Authorization: "Bearer " + this.staff.getToken,
        },
      })
      .then((response) => {
        this.PositionData = response.data;
      })
      .catch((error) => {
        if (error && error.response && error.response.status === 401) { 
          this.staff.logOut();
          localStorage.removeItem("web_token");
          localStorage.removeItem("web_user");
          this.$router.push("/login");
        }
      });
    },

    GetSalary() {
      axios.get("/api/position", {
        headers: {
          Authorization: "Bearer " + this.staff.getToken,
        },
      }).then((response) => {
        // Extract salary data from positions
        if (Array.isArray(response.data)) {
          this.SalaryData = response.data.map(pos => ({
            id: pos.id,
            name: pos.name || pos.title,
            salary: pos.salary
          }));
          } else {
            this.SalaryData = response.data;
          }
        }).catch((error) => {
            if (error && error.response && error.response.status === 401) { 
              console.log("Token expired, redirecting to login page.");
              this.staff.logOut();
              localStorage.removeItem("web_token");
              localStorage.removeItem("web_user");
              this.$router.push("/login");
            }
        });
    },

    cleanNumber(value) {
      if (!value) return 0;
      // Handle both string and number inputs
      const stringValue = String(value);
      // Remove all non-numeric characters except decimal point
      const cleaned = stringValue.replace(/[^\d.]/g, '');
      // Convert to float to handle decimal numbers properly
      return parseFloat(cleaned) || 0;
    },

    CalcTotal() {
      // For immediate feedback, calculate right away
      const salary = this.cleanNumber(this.FormPayroll.salary);
      const bonus = this.cleanNumber(this.FormPayroll.bonus);
      const del = this.cleanNumber(this.FormPayroll.del_salary);

      const total = salary + bonus - del;
      this.FormPayroll.amount = total;
    },
    
    // Debounced version for better performance during rapid typing
    CalcTotalDebounced: _.debounce(function() {
      this.CalcTotal();
    }, 300),
  },

  created() {
    this.GetPayroll();
    this.GetEmployee();
    this.GetDepartment();
    this.GetPosition();
    this.GetSalary();
  },

  watch: {
    'FormPayroll.employee_id'(newVal) {
      if (newVal) {
        const employee = this.EmployeeData.find(e => e.id === newVal);
        if (employee) {
          this.FormPayroll.department_id = employee.department_id || '';
          this.FormPayroll.position_id = employee.position_id || '';
          this.FormPayroll.department_name = employee.department_name || '';
          this.FormPayroll.position_name = employee.position_name || '';

          const position = this.PositionData.find(p => p.id === this.FormPayroll.position_id);
          if (position) {
            this.FormPayroll.salary = this.cleanNumber(position.salary);
            this.CalcTotal();
          }
        }
      }
    },

    'FormPayroll.position_id'(newVal) {
      if (newVal) {
        const position = this.PositionData.find(p => p.id === newVal);
        if (position) {
          this.FormPayroll.salary = this.cleanNumber(position.salary);
          this.CalcTotal();
        }
      }
    },

    SelectStatus() {
      this.GetPayroll();
    },

    Perpage() {
      this.GetPayroll();
    },

    Search(val) {
      if(val.length === 0 ) {
        this.GetPayroll();
      }
    },
    
    pay_month() {
      // Automatically search when date changes
      this.GetPayroll();
    },
  },

};
</script>

<style>
.status-paid {
  background-color: #d4edda;
  color: #155724;
  font-weight: bold;
  border: 1px solid #155724;
}

.status-pending {
  background-color: #f8d7da;
  color: #721c24;
  font-weight: bold;
  border: 1px solid #721c24;
}

.btn-paid {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 5px 12px;
  border-radius: 6px;
  font-size: 14px;
  cursor: pointer;
}

.btn-paid:hover {
  background-color: #218838;
}

</style>
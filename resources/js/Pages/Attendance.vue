<template lang="">
  <div class="card">
    <h5 class="card-header fw-bold">{{ $t('report_work') }}</h5>
    <div class="card-body">
      <!-- {{EmployeeData}} -->
      <div class="row">
        <div class="col-md-6">
          <div class="d-flex align-items-center mb-2">
            <span class="me-2">{{ $t('show_list') }}:</span>
            <select class="form-select w-auto" v-model="Perpage" @change="GetAttendance()">
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="30">30</option>
            </select>
          </div>
        </div>
        <div class="col-md-6 mb-2 d-flex justify-content-end">
          <div class="input-group w-auto me-2">
            <input type="text" class="form-control" v-model="Search" :placeholder="$t('search_employee')" @keyup.enter.enter="GetAttendance()"/>
            <button class="btn btn-primary" type="button" id="button-addon2" @click="GetAttendance()"><i class='bx bx-search-alt-2' ></i></button>
          </div>
          <button class="btn btn-success" type="button" @click="AddAttendance()">
            <span class="icon-base bx bx-plus icon-md"></span>{{ $t('add') }}
          </button>
        </div>
      </div>  
      
      <div class="table-responsive text-nowrap">
        <table class="table table-hover min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-100">
            <tr>
              <th>{{ $t('id') }}</th>
              <th>{{ $t('date') }}</th>
              <th>{{ $t('employee') }}</th>
              <th>{{ $t('check_in') }}</th>
              <th>{{ $t('break_start') }}</th>
              <th>{{ $t('break_end') }}</th>
              <th>{{ $t('check_out') }}</th>              
              <th>{{ $t('hour') }}</th>
              <!-- <th>ສະຖານະ</th> -->
              <!-- <th>ຈັດການ</th> -->
            </tr>
          </thead>
          <tbody v-if="AttendanceData.data.length > 0 " class="table-border-bottom-0">
            <tr v-for="items in AttendanceData.data" :key="items.id">
              <td>{{ items.id }}</td>
              <td>{{ $formatDate (items.date_here) }}</td>
              <td>{{ items.employee_fname }}</td>
              <td>{{ items.check_in }}</td>
              <td>{{ items.break_start }} </td>
              <td>{{ items.break_end }} </td>
              <td>{{ items.check_out }}</td>
              <td>{{ items.hour }}</td>      
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td colspan="8" class="text-center">
                <i class="bx bxs-data fs-4"></i>{{ $t('no_data') }}
              </td>
            </tr>
          </tbody>
        </table>
        <Pagination :pagination="AttendanceData" :offset="4" @paginate="GetAttendance($event)" />
      </div>
    </div>
  </div>

  <!-- Modal FormAttendance-->
  <div class="modal fade" id="Form_Attendance" data-bs-backdrop="static" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="backDropModalTitle">{{ $t('form_attendance') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-4">
                <label class="form-label fw-bold" for="basic-default-country">{{ $t('employee') }}: <span class="text-danger">*</span></label>
                <select class="form-select" v-model="FormAttendance.employee_id">
                  <option value="">{{ $t('select_employee') }}</option>
                  <option v-for="employee in EmployeeData" :key="employee.id" :value="employee.id">{{ employee.fname }}</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <DatePickerLao v-model="FormAttendance.date_here" :label="$t('date_here') + ':'" dateFormat="Y-m-d" :placeholder="$t('enter_date_here')" inputId="date_here"/>
              </div>
            </div>        
          </div>              
          <div class="row">
            <div class="col-md-6">
              <div class="mb-4">
                <TimePickerLao v-model="FormAttendance.check_in" :label="$t('check_in') + ':'" :placeholder="$t('enter_check_in')" inputId="check_in"/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <TimePickerLao v-model="FormAttendance.break_start" :label="$t('break_start') + ':'" :placeholder="$t('enter_break_start')" inputId="break_start"/>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-4">
                <TimePickerLao v-model="FormAttendance.break_end" :label="$t('break_end') + ':'" :placeholder="$t('enter_break_end')" inputId="break_end"/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <TimePickerLao v-model="FormAttendance.check_out" :label="$t('check_out') + ':'" :placeholder="$t('enter_check_out')" inputId="check_out"/>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="mb-4">
                <label for="hour" class="form-label fw-bold">{{ $t('hour') }}: <span class="text-danger">*</span></label>
                <input type="text" id="hour" v-model="FormAttendance.hour" class="form-control" :placeholder="$t('enter_hour')">
              </div>
            </div>
          </div>
        </div> 
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" :disabled="NosaveForm" @click="SaveAttendance()">{{ $t('save') }}</button>
          <button type="button" class="btn btn-label-secondary border" data-bs-dismiss="modal">{{ $t('close') }}</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {useStaff} from '../Staff/AuthLogin.js';
import DatePickerLao from '../Components/DatePickerLao.vue';
import TimePickerLao from '../Components/TimePickerLao.vue';

export default {
   components: {
    DatePickerLao,
    TimePickerLao,
   },
   setup() {
    const staff = useStaff();
    return {staff};
  },

  data() {
    return {
      Perpage:10,
      Search:'',
      FormType: true,
      EditID:"",
      AttendanceData: {
        data:[],
      },
      EmployeeData:[],
      FormAttendance: {
        date_here: '',
        employee_id: '',
        check_in:'',
        break_start: '', 
        break_end: '',
        check_out:'',
        hour:'',
      }
    }
  },

   computed: {
      NosaveForm() {
        if(this.FormAttendance.date_here === '' || 
            this.FormAttendance.check_in === '' ||
            this.FormAttendance.check_out === '' ||
            this.FormAttendance.hour === '' ||
            this.FormAttendance.employee_id == '' ||
            this.FormAttendance.break_start == '' ||
            this.FormAttendance.break_end == ''      
          ) {
            return true;
          } 
          else {
          return false;
          }
      }
    },

  methods: {
    AddAttendance() {
      this.FormType = true;
      this.EditID = '';
      $("#Form_Attendance").modal('show');

      this.FormAttendance = {
        date_here: '',
        employee_id: '',
        check_in: '',
        check_out: '',
        hour: '',
        break_start: '',
        break_end: '',
      }
    },

    SaveAttendance() {
      if(this.FormType) {
        axios.post('/api/attendance/add', this.FormAttendance,
          {
            headers: 
              {
                Authorization: 'Bearer ' + this.staff.getToken,
            },
          }
        ).then((response) => {
            if(response.data.success) {
              $("#Form_Attendance").modal("hide");
               this.$swal({
                  position: "top-end", 
                  icon: "success", 
                  title: response.data.message, 
                  showConfirmButton: false, 
                  timer: 2500, 
                  toast: true, 
              });
              this.GetAttendance();
            }
            else {
              $('#Form_Attendance').modal('hide');
              this.$swal({
                icon: "error",
                text: response.data.message,
                title: "ຜິດພາດ",
                showConfirmButton: false, 
                timer: 3500, 
              });
            }
          }).catch((error) => {
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
      }
      else {
        axios.post("/api/attendance/update/" + this.EditID, this.FormAttendance, {
            headers: {
              Authorization: "Bearer " + this.staff.getToken,
            },
          })
          .then((response) => {
            if (response.data.success) {
              this.$swal({
                position: "top-end",
                icon: "success",
                title: response.data.message,
                showConfirmButton: false,
                timer: 2500,
                toast: true,
              });
              $("#Form_Attendance").modal("hide");
              this.FormType = true;
              this.EditID = '';
              this.GetAttendance();
            } else {
              this.$swal({
                icon: "error",
                title: "ຜິດພາດ",
                text: response.data.message,
                showConfirmButton: false,
                timer: 3500,
              });
              this.GetAttendance();
            }
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
      }
    },

    GetAttendance(page = 1) {
        axios
          .get(`/api/attendance?page=${page}&search=${this.Search}&perpage=${this.Perpage}`, {
            headers: {
              Authorization: "Bearer " + this.staff.getToken,
            },
          })
          .then((response) => {
          this.AttendanceData = response.data;
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
  },

  created() {
    this.GetEmployee();
    this.GetAttendance();
  },

  watch: {
    Search(val) {
      if(val.length === 0 ) {
        this.GetAttendance();
      }
    },

    Perage() {
      this.GetAttendance();
    }
  }
}
</script>
<style>
  
</style>

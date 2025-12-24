<template>
  <div class="card">
    <h5 class="card-header fw-bold">{{ $t('leave_report') }}</h5>
    <!-- {{BreakTypeData}} -->
    <div class="card-body">
      <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6 mb-2 d-flex justify-content-end ">
          <div class="input-group w-auto me-2">
            <input type="text" class="form-control" v-model="Search"  :placeholder="$t('search_employee')" @keyup.enter="GetLeave()"/>
            <button class="btn btn-primary" type="button" id="button-addon2" @click="GetLeave()"><i class='bx bx-search-alt-2' ></i></button>
          </div>
          <button class="btn btn-info" type="button" @click="AddLeave()">
            <span class="icon-base bx bx-plus icon-md"></span>{{ $t('add') }}
          </button>
        </div>
      </div>  

      <div class="row g-2 align-items-end flex-wrap mb-2">
            <select class="form-select w-auto " v-model="Perpage" @change="GetLeave()">
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="30">30</option>
            </select>
        <!-- <div class="col-md-6 d-flex mb-2 align-items-end flex-md-nowrap flex-wrap gap-2"> -->
          <div class="col-md-auto w-auto">
            <DatePickerLao  v-model="start_date" :label="$t('start_date')" dateFormat="Y-m-d"  inputId="date_start"/>
          </div>

          <div class="col-md-auto w-auto">
            <DatePickerLao v-model="end_date" :label="$t('end_date')" dateFormat="Y-m-d" inputId="date_end"/>
          </div>

          <div class="col-md-auto">
            <button class="btn btn-primary px-4 mt-md-0" @click="SearchData()">
              <i class='bx bx-search-alt-2 me-1'></i> {{ $t('search') }}
            </button>
          </div>

          <div class="col-md-auto">
            <select class="form-select w-auto" v-model="SelectBreak">
              <option value="all">{{ $t('all_leave_types') }}</option>
              <option :value="items.id" v-for="items in BreakTypeData" :key="items.id">
                {{ items.name }}
              </option>
            </select>
          </div>

          <div class="col-md-auto">
            <select class="form-select w-auto" v-model="SelectStatus" @change="GetLeave()">
              <option value="all">{{ $t('all_statuses') }}</option>
              <option value="ລໍຖ້າອະນຸມັດ">{{ $t('pending') }}</option>
              <option value="ອະນຸມັດແລ້ວ">{{ $t('approved') }}</option>
              <option value="ປະຕິເສດ">{{ $t('rejected') }}</option>
            </select>
          </div>
        <!-- </div> -->
      </div>

      <div class="table-responsive text-nowrap">
      <table class="table table-borderless table-hover">
        <thead class="bg-gray-100">
          <tr>
            <th>{{ $t('id') }}</th>
            <th>{{ $t('employee') }}</th>
            <th>{{ $t('leave_type') }}</th>
            <th>{{ $t('start_date') }}</th>
            <th>{{ $t('end_date') }}</th>
            <th>{{ $t('number_of_days') }}</th>
            <th>{{ $t('status') }}</th>
            <th>{{ $t('actions') }}</th>
          </tr>
        </thead>
        <tbody v-if="LeaveData.data.length > 0">
          <tr v-for="items in LeaveData.data" :key="items.id">
            <td>{{items.id}}</td>
            <td>{{items.employee_fname}}</td>
            <td>{{items.break_type_name}}</td>
            <td>{{ $formatDate(items.start_date , 'lao') }}</td>
            <td>{{ $formatDate(items.end_date , 'lao') }}</td>
            <td>{{items.reason}}</td>
            <td>
              <span  :class="{
                  'fw-bold text-success': items.status === 'ອະນຸມັດແລ້ວ',
                  'fw-bold text-danger': items.status === 'ປະຕິເສດ',
                  'fw-bold text-warning': items.status === 'ລໍຖ້າອະນຸມັດ'
                }">
                <i v-if="items.status === 'ລໍຖ້າອະນຸມັດ'" class="bx bx-bell bx-tada text-warning fs-5 me-1" title="ລໍຖ້າອະນຸມັດ"></i>
                <i v-if="items.status === 'ອະນຸມັດແລ້ວ'" class="bx bx-check-circle text-success fs-5 me-1" title="ອະນຸມັດແລ້ວ"></i>
                <i v-if="items.status === 'ປະຕິເສດ'" class="bx bx-x-circle text-danger fs-5 me-1" title="ປະຕິເສດ"></i>
                {{ items.status }}
              </span>
            </td>
            <td>
              <div class="dropdown" v-if="items.status === 'ລໍຖ້າອະນຸມັດ'">
                <button type="button" class="btn btn-sm btn-light border dropdown-toggle text-primary" data-bs-toggle="dropdown">
                  <i class="bx bx-bell bx-tada"></i>  {{ $t('change_status') }}
                </button>
                <div class="dropdown-menu shadow-sm border-0">
                  <a class="dropdown-item text-success fw-bold d-flex align-items-center" href="javascript:void(0);" @click="UpdateStatus(items.id, 'ອະນຸມັດແລ້ວ')">
                    <i class="bx bx-check-circle text-success fs-5 me-1"></i>{{ $t('approved') }}
                  </a>
                  <a class="dropdown-item text-danger fw-bold d-flex align-items-center" href="javascript:void(0);" @click="UpdateStatus(items.id, 'ປະຕິເສດ')">
                    <i class="bx bx-x-circle text-danger fs-5 me-1"></i> {{ $t('rejected') }}
                  </a>
                </div>
              </div>

              <!-- ສະແດງໄອຄອນຖ້າບໍ່ມີປຸ່ມ -->
              <div v-else>
                <i v-if="items.status === 'ອະນຸມັດແລ້ວ'" class="bx bx-check-circle text-success fs-4" title="ອະນຸມັດແລ້ວ"></i>
                <i v-if="items.status === 'ປະຕິເສດ'" class="bx bx-x-circle text-danger fs-4" title="ປະຕິເສດ"></i>
              </div>
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="8" class="text-center">
                <i class="bx bxs-data fs-4"></i> {{ $t('no_data') }}
              </td>
          </tr>
        </tbody>
      </table>
      <Pagination :pagination="LeaveData" :offset="4" @paginate="GetLeave($event)" />
    </div>
    </div>
  </div>

  <div class="modal fade" id="FormLeave" data-bs-backdrop="static" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="backDropModalTitle">{{ $t('leave_form_title') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-4">
                <label class="form-label fw-bold" for="basic-default-country">{{ $t('employee') }}: <span class="text-danger">*</span></label>
                <select class="form-select" v-model="FormLeave.employee_id">
                  <option value="">{{ $t('select_employee') }}...</option>
                  <option v-for="employee in EmployeeData" :key="employee.id" :value="employee.id">{{employee.fname}}</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label class="form-label fw-bold" for="basic-default-country">{{ $t('leave_type') }}: <span class="text-danger">*</span></label>
                <select class="form-select" v-model="FormLeave.break_type_id">
                  <option value="">{{ $t('select_leave_type') }}...</option>
                  <option v-for="break_type in BreakTypeData" :key="break_type.id" :value="break_type.id">{{break_type.name}}</option>
                  <option></option>
                </select>
              </div>
            </div>          
          </div>              
          <div class="row">
            <div class="col-md-6">
              <div class="mb-4">
                <DatePickerLao v-model="FormLeave.start_date" :label="$t('leave_start_date')" dateFormat="Y-m-d" inputId="start_date_form"/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <DatePickerLao v-model="FormLeave.end_date" :label="$t('leave_end_date')" dateFormat="Y-m-d" inputId="end_date_form"/>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-4">
                <label class="form-label fw-bold" for="basic-default-country">{{ $t('status') }}: <span class="text-danger">*</span></label>
                <select class="form-select" v-model="FormLeave.status">
                  <option value="">{{ $t('select_status') }}...</option>
                  <option value="ລໍຖ້າອະນຸມັດ">{{ $t('pending_approval') }}</option>
                  <option value="ອະນຸມັດແລ້ວ">{{ $t('approved') }}</option>
                  <option value="ປະຕິເສດ">{{ $t('rejected') }}</option>
                </select>
              </div>
            </div>     
            <div class="col-md-6">
              <div class="mb-4">
                <label for="reason" class="form-label fw-bold">{{ $t('leave_days') }}: <span class="text-danger">*</span></label>
                <input type="text" id="reason" v-model="FormLeave.reason" class="form-control" :placeholder="$t('leave_days') + '...'" />
              </div>
            </div>
          </div>
        </div> 
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" :disabled="IsFormValid" @click="SaveLeave()">{{ $t('save') }}</button>
          <button type="button" class="btn btn-label-secondary border" data-bs-dismiss="modal">{{ $t('close') }}</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {useStaff} from '../Staff/AuthLogin.js';
import DatePickerLao from '../Components/DatePickerLao.vue';

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
      start_date:'',
      end_date:'',
      Search:'',
      Perpage:10,
      FormType: true,
      EditID: "",
      EmployeeData:[],
      BreakTypeData:[],
      StatusData:[],
      LeaveData:{
        data:[],
      },
      SelectBreak: 'all',
      SelectStatus: 'all',
      FormLeave: {
        employee_id:'',
        start_date:'',
        end_date:'',
        break_type_id:'',
        reason:'',
        status:'',
      },
    };
    
  },

  computed: {
    IsFormValid() {
      if(this.FormLeave.break_type_id === '' ||
        this.FormLeave.employee_id === '' ||
        this.FormLeave.start_date === '' ||
        this.FormLeave.end_date === '' ||
        this.FormLeave.reason === '' ||
        this.FormLeave.status === '' 
      ) {
        return true;
      } 
      else {
        return false;
      }
    }
  },

  methods: {
    AddLeave() {
      this.FormType = true;
      this.EditID = '';
      $("#FormLeave").modal('show');

      // clear form
      this.FormLeave = {
        employee_id:'',
        start_date:'',
        end_date:'',
        break_type_id:'',
        reason:'',
        status:'',
      }
    },

    SaveLeave() {
      if(this.FormType) {
         axios.post('/api/leave/add', this.FormLeave,
          {
            headers: 
              {
                Authorization: 'Bearer ' + this.staff.getToken,
            },
          }
        ).then((response) => {
            if(response.data.success) {
              $("#FormLeave").modal("hide");
               this.$swal({
                  position: "top-end", 
                  icon: "success", 
                  title: response.data.message, 
                  showConfirmButton: false, 
                  timer: 2500, 
                  toast: true, 
              });
              this.GetLeave();
            }
            else {
              $('#FormLeave').modal('hide');
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
        // update leave
        axios.post("/api/leave/update/" + this.EditID, this.FormLeave, {
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
              // hide the modal and reset form after successful update
              $("#FormLeave").modal("hide");
              this.FormType = true;
              this.EditID = '';
              this.GetLeave(); // refresh product list
            } else {
              this.$swal({
                icon: "error",
                title: "ຜິດພາດ",
                text: response.data.message,
                showConfirmButton: false,
                timer: 3500,
              });
              this.GetLeave();
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

    SearchData() {
      axios
        .get(`/api/leave?search=${this.Search}&break_type_id=${this.SelectBreak}&start_date=${this.start_date}&end_date=${this.end_date}`, {
          headers: {
            Authorization: "Bearer " + this.staff.getToken,
          },
        })
        .then((response) => {
          this.LeaveData = response.data;
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


    // ການປ່ຽນສະຖານະ
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
          axios.post(`/api/leave/status/${id}`, { status: status }, {
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
              this.GetLeave(); // ດຶງຂໍ້ມູນໃໝ່ຫຼັງຈາກອະນຸມັດ
            }
          })
          .catch((error) => {
            console.log(error);
          });
        }
      });
    },

    GetLeave(page = 1) {
      axios
        .get(`/api/leave?page=${page}&search=${this.Search}&perpage=${this.Perpage}&break_type_id=${this.SelectBreak}&start_date=${this.start_date}&end_date=${this.end_date}&status=${this.SelectStatus}`, {
          headers: {
            Authorization: "Bearer " + this.staff.getToken,
          },
        })
        .then((response) => {
          this.LeaveData = response.data;
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

    GetBreakType() {
        axios
          .get("/api/break_type", {
            headers: {
              Authorization: "Bearer " + this.staff.getToken,
            },
          })
          .then((response) => {
            this.BreakTypeData = response.data;
          })
          .catch((error) => {
            if (error && error.response && error.response.status === 401) {
                console.log("Token expired, redirecting to login page.");
                // clear token
                this.staff.logOut();
                localStorage.removeItem("web_token");
                localStorage.removeItem("web_user");
                this.$router.push("/login");
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
        // console.log("Employee Data =>", response.data);
          this.EmployeeData = response.data;
        })
        .catch((error) => {
          if (error && error.response && error.response.status === 401) { 
            console.log("Token expired, redirecting to login page.");
            // clear token
            this.staff.logOut();
            //clear local Storage
            localStorage.removeItem("web_token");
            localStorage.removeItem("web_user");
            this.$router.push("/login");
          }
        });
      },
  },

    created() {
      this.GetEmployee();
      this.GetLeave();
      this.GetBreakType();
      this.SearchData();
    },

    watch: {
    Search(val) {
      if(val.length === 0 ) {
        this.GetLeave();
      }
    },

    SelectBreak() {
      this.GetLeave();
    },

    SelectStatus() {
      this.GetLeave();
    },

    Perpage() {
      this.GetLeave();
    }
  }
};
</script>

<style>
/* .form-group {
  display: flex;
  flex-direction: column;
  min-width: 200px;
}

.form-control,
.form-select {
  width: 100%;
  height: 38px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 14px;
}

label {
  font-weight: 600;
  margin-bottom: 5px;
}  */

  /* ===== ຈັດໃຫ້ປຸ່ມຄົ້ນຫາກັບປຸ່ມເພີ່ມສູງເທົ່າກັນ ===== */
.input-group .form-control {
  height: 42px;
  border-radius: 8px 0 0 8px;
}

.input-group .btn-primary {
  height: 42px;
  border-radius: 0 8px 8px 0;
  background-color: #7266f0;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn-info {
  height: 42px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  font-weight: 500;
  padding: 0 18px;
}

/* ຈັດໃຫ້ທັງສອງຢູ່ໃນແນວຕັ້ງດຽວກັນ */
.d-flex.justify-content-end {
  align-items: center;
}


</style>
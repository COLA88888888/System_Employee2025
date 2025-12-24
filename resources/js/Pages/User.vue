<template>
  <div class="card">
    <h5 class="card-header fw-bold">ລາຍງານຜູ້ນຳໃຊ້ງານລະບົບ</h5>
    <div class="card-body">
       <div class="row"> 
          <div class="col-md-6"></div>
          <div class="col-md-6 mb-2 d-flex justify-content-end">
            <button class="btn btn-success" type="button" @click="AddUsers()"><i class="bx bx-plus"></i> {{ $t('add') }}</button>
          </div>
       </div>
      <!-- Users Table -->
      <div class="table-responsive text-nowrap">
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th class="text-center fw-bold">ລະຫັດ</th>
              <th class="text-center fw-bold">ຊື່ຜູ້ໃຊ້ງານ</th>
              <th class="text-center fw-bold">ອີເມວ໌</th>
              <th class="text-center fw-bold">ບົດບາດ</th>
              <th class="text-center fw-bold">ວັນທີສ້າງ</th>
              <th class="text-center fw-bold">ເວລາສ້າງ</th>
              <th class="text-center fw-bold">ຈັດການ</th>
            </tr>
          </thead>
          <tbody v-if="UsersData.length > 0">
            <tr v-for="items in UsersData" :key="items.id">
              <td class="text-center">{{ items.id }}</td>
              <td class="text-center">{{ items.name }}</td>
              <td class="text-center">{{ items.email }}</td>
              <td class="text-center">
                <span>{{ items.role }}</span>
              </td>
              <td class="text-center">{{ $formatDate(items.created_at) }}</td>
              <td class="text-center">{{ $formatDate(items.created_at, 'time') }}</td>
              <td class="text-center">
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
                  <div class="dropdown-menu" style="">
                    <a class="dropdown-item text-warning fw-bold" @click="EditUsers(items.id)" href="javascript:void(0);"><i class="icon-base bx bx-edit-alt me-1"></i>{{ $t('edit') }}</a>
                    <a class="dropdown-item text-danger fw-bold" @click="DeleteUsers(items.id)" href="javascript:void(0);"><i class="icon-base bx bx-trash me-1"></i>{{ $t('delete') }}</a>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td colspan="6" class="text-center">
                <i class="bx bx-info-circle"></i> ບໍ່ມີຂໍ້ມູນຜູ້ໃຊ້
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="Form_Users" data-bs-backdrop="static" tabindex="-1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="backDropModalTitle">{{ $t('form user') }}</h5>
                 <!-- {{FormPosition}} -->
                <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col md-6">
                        <div class="mb-4">
                          <label class="form-label">ຊື່</label>
                          <input type="text" class="form-control" v-model="FormUser.name" placeholder="ປ້ອນຊື່ຜູ້ໃຊ້">
                        </div>
                        
                        <div class="mb-4">
                          <label for="nameBackdrop" class="form-label">ອີເມວ: <span class="text-danger">*</span></label>
                            <div class="input-group">
                              <input type="email" class="form-control" v-model="FormUser.email" placeholder="ປ້ອນອີເມວ໌">
                            </div>
                        </div>
                      </div>
                      <div class="col md-6">
                        <div class="mb-4">
                          <label for="nameBackdrop" class="form-label">ລະຫັດຜ່ານ: <span class="text-danger">*</span></label>
                            <div class="input-group">
                              <input type="password" class="form-control" v-model="FormUser.password" placeholder="ປ້ອນລະຫັດຜ່ານ">
                            </div>
                        </div>

                        <div class="mb-4">
                          <label for="nameBackdrop" class="form-label">ຢືນຢັນລະຫັດຜ່ານ: <span class="text-danger">*</span></label>
                            <div class="input-group">
                              <input type="password" class="form-control" v-model="FormUser.password_confirmation" placeholder="ປ້ອນລະຫັດຜ່ານອີກຄັ້ງ">
                            </div>
                        </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="mb-6">
                        <label class="form-label" for="basic-default-country">{{ $t('role') }}: <span class="text-danger">*</span></label>
                        <select class="form-select" v-model="FormUser.role" id="role">
                          <option value="admin">Admin</option>
                          <option value="manager">Manager</option>
                          <option value="employee" selected>Employee</option>
                        </select>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-primary" :disabled="IsFormValid" @click="SaveUsers()">{{ $t('save') }}</button>
                  <button type="button" class="btn btn-label-secondary border" data-bs-dismiss="modal">{{ $t('close') }}</button>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
import { useStaff } from '../Staff/AuthLogin.js';

export default {
  setup() {
    const staff = useStaff();
    return { staff };
  },
  data() {
    return {
      FormType: true,
      EditID: "",     
      UsersData: [],
      FormUser: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'employee'
      },
    };
  },

  computed: {
      IsFormValid() {
        if(this.FormUser.name === '' || this.FormUser.email === '' || this.FormUser.password === '' || this.FormUser.password_confirmation === '') {
          return true;
        } 
        else {
          return false;
        }
      }
    },

  methods: {

    AddUsers() {
      this.FormType = true;
      this.EditID = "";
      $('#Form_Users').modal('show');

      this.FormUser = {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'employee'
      };
    },

    SaveUsers() {
      if(this.FormType) {
        axios.post('/api/user/add', this.FormUser, {
          headers: {
            Authorization: 'Bearer ' + this.staff.token,
          },
        }).then((response) => {
          if(response.data.success) {
            $('#Form_Users').modal('hide');
            this.$swal({
              position: "top-end", 
              icon: "success", 
              title: response.data.message, 
              showConfirmButton: false, 
              timer: 2500, 
              toast: true, 
            });
            this.GetUsers();
          }
          else {
            $('#Form_Users').modal('hide');
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

                // clear token
                this.staff.logOut();

                //clear local Storage
                localStorage.removeItem("web_token");
                localStorage.removeItem("web_user");

                // redirect to login page
                this.$router.push("/login");
              });
            }
          });
      }
      else {
        // Update existing user
        axios.post("/api/user/update/" + this.EditID, this.FormUser, {
          headers: {
            Authorization: "Bearer " + this.staff.token,
          },
        }).then((response) => {
          if (response.data.success) {
            $('#Form_Users').modal('hide');
            this.$swal({
              position: "top-end",
              icon: "success",
              title: response.data.message,
              showConfirmButton: false,
              timer: 2500,
              toast: true,
            });
            this.GetUsers();
          } else {
            $('#Form_Users').modal('hide');
            this.$swal({
              icon: "error",
              text: response.data.message,
              title: "ຜິດພາດ",
              showConfirmButton: false,
              //timer: 3500,
            });
          }
        }).catch((error) => {
          console.log(error);
        });
      }
    },

    EditUsers(id) {
        this.FormType = false;
        this.EditID = id;
        // this.FormUser.password = "";

        axios.get("/api/user/edit/" + id, {
          headers: {
            Authorization: "Bearer " + this.staff.getToken,
          },
        })
        .then((response) => { 
          if (response.data.success) {
            $('#Form_Users').modal('show');  
            this.FormUser = response.data.user;            
          }
        })
        .catch((error) => {
          console.log(error);
          // ໂຄ້ດນີ້ແມ່ນ ເວລາ token ໝົດເວລາທີ່ກຳນົດໄວ້ ແລ້ວ ຈະເດັ້ງໄປໜ້າ login ໃໝ່
          if (error && error.response && error.response.status === 401) {
            this.$swal({
              title: "Token ໝົດອາຍຸແລ້ວ",
              text: "ກະລຸນາເຂົ້າລະບົບໃໝ່",
              icon: "error",
            }).then(() => {
              console.log("Token expired, redirecting to login page.");

              // clear token
              this.store.logOut();

              //clear local Storage
              localStorage.removeItem("web_token");
              localStorage.removeItem("web_user");

              // redirect to login page
              this.$router.push("/login");
            });
          }
        });
      },

       DeleteUsers(id) {
        this.$swal({
          title: "ເຈົ້າຕ້ອງການລົບຂໍ້ມູນ ຫຼື ບໍ່?",
          text: "ກົດຢືນຢັນເພື່ອລົບຂໍ້ມູນນີ້ !",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "ຢືນຢັນ",
          cancelButtonText: "ຍົກເລີກ", 
        }).then((result) => {
        if (result.isConfirmed) {
          axios
            .delete("/api/user/delete/" + id, {
              headers: {
                Authorization: "Bearer " + this.staff.getToken,
              },
            })
            .then((response) => {
              if (response.data.success) {
                this.GetPosition(); // refresh category list
                this.$swal({
                  icon: "success", 
                  title: "ລົບຂໍ້ມູນສຳເລັດ",
                  showConfirmButton: false, 
                  timer: 2500, 
                });
              } else {
                // show error message
                this.$swal({
                  icon: "error",
                  text: response.data.message,
                  title: "ຜິດພາດ",
                  showConfirmButton: false,
                  timer: 3500,
                });
              }
            })
            .catch((error) => {
              console.log(error);
              // ໂຄ້ດນີ້ແມ່ນ ເວລາ token ໝົດເວລາທີ່ຕກຳນົດໄວ້ ແລ້ວ ຈະເດັ້ງໄປໜ້າ login ໃໝ່
              if (error && error.response && error.response.status === 401) {
                this.$swal({
                  title: "Token ໝົດອາຍຸແລ້ວ",
                  text: "ກະລຸນາເຂົ້າລະບົບໃໝ່ !",
                  icon: "error",
                }).then(() => {
                  console.log("Token expired, redirecting to login page.");

                  // clear token
                  this.store.logOut();

                  //clear local Storage
                  localStorage.removeItem("web_token");
                  localStorage.removeItem("web_user");

                  // redirect to login page
                  this.$router.push("/login");
                });
              }
            });
        }
      });
    },

    GetUsers() {
        axios.get("/api/user", {
            headers: {
              Authorization: "Bearer " + this.staff.getToken,
            },
          })
          .then((response) => {
          this.UsersData = response.data;
          })
          .catch((error) => {
            if (error && error.response && error.response.status === 401) {       
                console.log("Token expired, redirecting to login page.");
                // clear token
                this.staff.logOut();
                //clear local Storage
                localStorage.removeItem("web_token");
                localStorage.removeItem("web_user");
                // redirect to login page
                this.$router.push("/login");
            }
          });
      },
  },

  created() {
    this.GetUsers();
  },
};
</script>

<style scoped>
.badge {
  font-size: 0.75em;
}
</style>
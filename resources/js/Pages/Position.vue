<template>
      <div class="row">
    <div class="col-lg-6">
        <div class="card">
          <div class="card-header pb-0 d-flex justify-between align-items-center">
            <h5 class="card-title fw-bold">{{ $t('report_positions') }}</h5>
            <button type="button" class="btn rounded-pill btn-icon btn-primary" @click="AddPosition()" v-if="$can('settings.edit')">
                  <span class="icon-base bx bx-plus icon-md"></span>
            </button>
          </div>
          <div class="card-body p-0">
            <!-- {{DepartmentData}} -->
              <div class="list-group list-group-flush">
                <div v-for="items in PositionData" :key="items.id" class="list-group-item list-group-item-action d-flex justify-content-between">
                  <span>{{items.name}}</span>
                  <span v-if="$can('settings.edit')"><i class='bx bxs-edit cursor-pointer text-warning' @click="EditPosition(items.id)"></i> | <i class='bx bx-trash cursor-pointer text-danger' @click="DeletePosition(items.id)"></i></span>
                </div>
              </div>
          </div>
        </div>
    </div>
  </div>

  <div class="modal fade" id="FormPosition" data-bs-backdrop="static" tabindex="-1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="backDropModalTitle">{{ $t('form_position') }}</h5>
                <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col mb-6">
                          <label for="nameBackdrop" class="form-label">{{ $t('position') }}: <span class="text-danger">*</span></label>
                          <input type="text" id="nameBackdrop" v-model="name" class="form-control" :placeholder="$t('enter_position')">
                      </div>
                    </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-primary" :disabled="IsFormValid" @click="SavePosition()">{{ $t('save') }}</button>
                  <button type="button" class="btn btn-label-secondary border" data-bs-dismiss="modal">{{ $t('close') }}</button>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
import {useStaff} from '../Staff/AuthLogin.js';
export default {
  setup() {
    const staff = useStaff();
    return {staff};
  },

  data() {
        return {
            name: "",
            FormType: true,
            EditID: "",
            PositionData: [],

            // options: {
            //   numeral: true, 
            //   numeralPositiveOnly: true, 
            //   noImmediatePrefix: true, 
            //   rawValueTrimPrefix: true, 
            //   numeralIntegerScale: 10, 
            //   numeralDecimalScale: 2, 
            //   numeralDecimalMark: ".", 
            //   delimiter: ",", 
            // },
        };
    },

    computed: {
      IsFormValid() {
        return this.name === "";
      }
    },

    methods: {
      // ຟັ່ງຊັ່ນສຳລັບແປງລາຄາມີຕົວເລກເສດເປັນລາຄາທີ່ມີຈຸດ
      // formatPrice(value) {
      //   let val = (value / 1).toFixed(0).replace(".", ",");
      //   return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      // },

      AddPosition() {
        this.FormType = true;
        this.EditID = '';
        $('#FormPosition').modal('show');

        // reset form fields
        this.name = '';
      },

      SavePosition() {
        if(this.FormType) {
          // add position
          axios.post('/api/position/add', {
              name: this.name,
          }, 
            {
              headers: 
                {
                  Authorization: 'Bearer ' + this.staff.getToken,
                },
            }
          ).then((response) => {
            if(response.data.success) {
              $("#FormPosition").modal("hide");
               this.$swal({
                  position: "top-end", 
                  icon: "success", 
                  title: response.data.message, 
                  showConfirmButton: false, 
                  timer: 2500, 
                  toast: true, 
              });
              this.GetPosition();
            }
            else {
              $('#FormPosition').modal('hide');
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
          // update position
          axios.post("/api/position/update/" + this.EditID, {
            name: this.name,
          }, 
            {
              headers: {
                Authorization: "Bearer " + this.staff.getToken,
              },
            }
          )
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
              $("#FormPosition").modal("hide");
              this.FormType = true;
              this.EditID = '';
            } else {
              this.$swal({
                icon: "error",
                title: "ຜິດພາດ",
                text: response.data.message,
                showConfirmButton: false,
                timer: 3500,
              });
              this.GetPosition();
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
      },

      EditPosition(id) {
        this.FormType = false;
        this.EditID = id;

        axios.get("/api/position/edit/" + id, {
          headers: {
            Authorization: "Bearer " + this.staff.getToken,
          },
        })
        .then((response) => { 
          if (response.data.success) {
            $('#FormPosition').modal('show');  
            this.FormPosition = response.data.position;            
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

      DeletePosition(id) {
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
            .delete("/api/position/delete/" + id, {
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

      GetPosition() {
        axios
          .get("/api/position", {
            headers: {
              Authorization: "Bearer " + this.staff.getToken,
            },
          })
          .then((response) => {
          this.PositionData = response.data;
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
      this.GetPosition();
    }
};
</script>

<style>

</style>
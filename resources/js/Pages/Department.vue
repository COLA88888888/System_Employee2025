<template>  
  <div class="row">
    <div class="col-lg-6">
        <div class="card">
          <div class="card-header pb-0 d-flex justify-between align-items-center">
            <h5 class="card-title fw-bold">{{ $t('report_departments') }}</h5>
            <button type="button" class="btn rounded-pill btn-icon btn-primary" @click="AddDepartment()">
                  <span class="icon-base bx bx-plus icon-md"></span>
            </button>
          </div>
          <div class="card-body p-0">
            <!-- {{DepartmentData}} -->
              <div class="list-group list-group-flush">
                <div v-for="items in DepartmentData" :key="items.id" class="list-group-item list-group-item-action d-flex justify-content-between">
                  <span>{{items.name}}</span>
                  <span><i class='bx bxs-edit cursor-pointer text-warning' @click="EditDepartment(items.id)"></i> | <i class='bx bx-trash cursor-pointer text-danger' @click="DeleteDepartment(items.id)"></i></span>
                </div>
              </div>
          </div>
        </div>
    </div>
  </div>

  <div class="modal fade" id="FormDepartment" data-bs-backdrop="static" tabindex="-1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="backDropModalTitle">{{ $t('form_department') }}</h5>
                <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col mb-6">
                          <label for="nameBackdrop" class="form-label">{{ $t('department') }}: <span class="text-danger">*</span></label>
                          <input type="text" id="nameBackdrop" v-model="name" class="form-control" :placeholder="$t('enter_department')">
                      </div>
                    </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-primary" :disabled="CheckForm" @click="SaveDepartment()">{{ $t('save') }}</button>
                  <button type="button" class="btn btn-label-secondary border" data-bs-dismiss="modal">{{ $t('close') }}</button>
              </div>
          </div>
      </div>
  </div>
  
</template>

<script>
import {useStaff} from '../Staff/AuthLogin';
export default {
  setup() {
    const staff = useStaff();
    return {staff};
  },
  data() {
    return {
      name: '',
      FormType:true,
      EditID: '',
      DepartmentData: [],
    }
  },
  
  computed: {
    CheckForm() {
      return this.name === '';
    }
  },

  methods: {
    showAlert() {
      // Use sweetalert2
      this.$swal({
        title: "ເຈົ້າຕ້ອງການລົບຂໍ້ມູນ ຫຼື ບໍ່?",
        text: "ກົດຢືນຢັນເພື່ອລົບຂໍ້ມູນນີ້ !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6", // ສີຂອງປຸ່ມຢືນຢັນສີຟ້າ
        cancelButtonColor: "#d33", // ສີຂອງປຸ່ມສີແດງ
        confirmButtonText: "ຢືນຢັນ", //ຂໍ້ຄວາມປຸ່ມຢືນຢັນ
        cancelButtonText: "ຍົກເລີກ", //ຂໍ້ຄວາມປຸ່ມຍົກເລີກ
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: "Deleted!",
            text: "Your file has been deleted.",
            icon: "success",
          });
        }
      });
    },
    AddDepartment() {
      $('#FormDepartment').modal('show');
    },

    EditDepartment(id) {
      this.FormType = false,
      this.EditID = id;
        axios
        .get("/api/department/edit/" + id, {
          headers: {
            Authorization: "Bearer " + this.staff.getToken,
          },
        })
        .then((response) => {
          this.name = response.data.name; // set name to input
          $('#FormDepartment').modal('show');
        })
        .catch((error) => {
          console.log(error);
          // ໂຄ້ດນີ້ແມ່ນ ເວລາ token ໝົດເວລາທີ່ຕກຳນົດໄວ້ ແລ້ວ ຈະເດັ້ງໄປໜ້າ login ໃໝ່
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

    SaveDepartment() {
      if (this.FormType) {
        // ບັນທຶກຂໍ້ມູນໃໝ່
        axios.post("/api/department/add",
            {
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
              this.name = "";
              $("#FormDepartment").modal("hide"); //ບັນທຶກຂໍ້ມູນແລ້ວຍແລ້ວປຟອມຫາຍໄປ
              this.GetDepartment(); //ບັນທືກຂໍ້ມູນສຳເລັດແລ້ວສະແດງໃນຕາຕະລາງ

              this.$swal({
                position: "top-end",
                icon: "success", //ໄອຄອນສຳເລັດ
                title: "ບັນທຶກຂໍ້ມູນສຳເລັດ", // ຂໍ້ຄວາມຂອງການແຈ້ງເຕືອນ
                showConfirmButton: false, // ປິດປຸ່ມຢືນຢັນ
                timer: 2500, //ກຳນົດເວລາໃນການແຈ້ງເຕືອນ
                toast: true, // ໃຊ້ແບບທອດ
              });

            } else {
              //show error message
              $("#FormDepartment").modal("hide");
              this.$swal({
                //position: "top", // ຕຳແໜ່ງຂອງການແຈ້ງເຕືອນຢູ່ທິ່ນສຸດ
                icon: "error", //ໄອຄອນສຳເລັດ
                text: response.data.message, // ຂໍ້ຄວາມຂອງການແຈ້ງເຕືອນ
                title: "ຜິດພາດ", // ຂໍ້ຄວາມຂອງການແຈ້ງເຕືອນ
                showConfirmButton: false, // ປິດປຸ່ມຢືນຢັນ
                timer: 3500, //ກຳນົດເວລາໃນການແຈ້ງເຕືອນ
                //toast: true, // ໃຊ້ແບບທອດ
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
        // update Department
        axios.post("/api/department/update/" + this.EditID,
            {
              name: this.name,
            },
            // ທຸກໆຄັ້ງຕ້ອງໄດ້ຜ່ານ Token
            {
              headers: { Authorization: "Bearer " + this.staff.getToken },
            }
          )
          .then((response) => {
            if (response.data.success) {
              this.name = "";
              $("#FormDepartment").modal("hide");
              this.GetDepartment();

              this.$swal({
                position: "top-end", // ຕຳແໜ່ງຂອງການແຈ້ງເຕືອນຢູ່ທິ່ນສຸດ
                icon: "success", //ໄອຄອນສຳເລັດ
                title: "ແກ້ໄຂຂໍ້ມູນສຳເລັດ", // ຂໍ້ຄວາມຂອງການແຈ້ງເຕືອນ
                showConfirmButton: false, // ປິດປຸ່ມຢືນຢັນ
                timer: 2500, //ກຳນົດເວລາໃນການແຈ້ງເຕືອນ
                toast: true, // ໃຊ້ແບບທອດ
              });
            } else {
              console.log(response.data.message);
            }
          });
      }
    },

    DeleteDepartment(id) {
      // detele Darpartment
      this.$swal({
        title: "ເຈົ້າຕ້ອງການລົບຂໍ້ມູນ ຫຼື ບໍ່?",
        text: "ກົດຢືນຢັນເພື່ອລົບຂໍ້ມູນນີ້ !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6", // ສີຂອງປຸ່ມຢືນຢັນສີຟ້າ
        cancelButtonColor: "#d33", // ສີຂອງປຸ່ມສີແດງ
        confirmButtonText: "ຢືນຢັນ", //ຂໍ້ຄວາມປຸ່ມຢືນຢັນ
        cancelButtonText: "ຍົກເລີກ", //ຂໍ້ຄວາມປຸ່ມຍົກເລີກ
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .delete("/api/department/delete/" + id, {
              headers: {
                Authorization: "Bearer " + this.staff.getToken,
              },
            })
            .then((response) => {
              if (response.data.success) {
                this.GetDepartment(); // refresh category list
                this.$swal({
                  //position: "top", // ຕຳແໜ່ງຂອງການແຈ້ງເຕືອນຢູ່ທິ່ນສຸດ
                  icon: "success", //ໄອຄອນສຳເລັດ
                  title: "ລົບຂໍ້ມູນສຳເລັດ", // ຂໍ້ຄວາມຂອງການແຈ້ງເຕືອນ
                  showConfirmButton: false, // ປິດປຸ່ມຢືນຢັນ
                  timer: 2500, //ກຳນົດເວລາໃນການແຈ້ງເຕືອນ
                  //toast: true, // ໃຊ້ແບບທອດ
                });
              } else {
                // show error message

                this.$swal({
                  //position: "top", // ຕຳແໜ່ງຂອງການແຈ້ງເຕືອນຢູ່ທິ່ນສຸດ
                  icon: "error", //ໄອຄອນສຳເລັດ
                  text: response.data.message, // ຂໍ້ຄວາມຂອງການແຈ້ງເຕືອນ
                  title: "ຜິດພາດ", // ຂໍ້ຄວາມຂອງການແຈ້ງເຕືອນ
                  showConfirmButton: false, // ປິດປຸ່ມຢືນຢັນ
                  timer: 3500, //ກຳນົດເວລາໃນການແຈ້ງເຕືອນ
                  //toast: true, // ໃຊ້ແບບທອດ
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

    GetDepartment() {
      axios
        .get("/api/department", {
          headers: {
            Authorization: "Bearer " + this.staff.getToken,
          },
        })
        .then((response) => {
          this.DepartmentData = response.data;
        })
        .catch((error) => {
          // console.log(error);
          // console.log(error.response.status);
          // ໂຄ້ດນີ້ແມ່ນ ເວລາ token ໝົດເວລາທີ່ຕກຳນົດໄວ້ ແລ້ວ ຈະເດັ້ງໄປໜ້າ login ໃໝ່
          if (error && error.response && error.response.status === 401) {
            this.$swal({
              title: "Token ໝົດອາຍຸແລ້ວ",
              text: "ກະລຸນາເຂົ້າລະບົບໃໝ່ !",
              icon: "error",
              // timer: 2500,
              // showConfirmButton: false,
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
    },
  },

  created() {
    this.GetDepartment();
  }
};
</script>

<style scoped>
</style>
<template>
<!-- <div class="container-fluid py-4"> -->
    <div class="row g-4 justify-content-center">     
        <!-- <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h6 class="m-0 fw-bold">ປະເພດຜູ້ໃຊ້</h6>
                    <button type="button" class="btn rounded-pill btn-sm btn-icon btn-light" @click="Addroles()">
                      <i class="bx bx-plus bx-sm cursor-pointer"></i>
                    </button>
                </div>
                <div class="card-body p-0">
                  <div class="list-group list-group-flush">
                      <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span>cashier</span>
                        <span><i class='bx bxs-edit cursor-pointer text-warning' @click="Editroles(items.id)"></i> | <i class='bx bx-trash cursor-pointer text-danger' @click="Deleteroles(items.id)"></i></span>
                      </div>
                  </div>
                </div>
            </div>
        </div> -->

        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    
                    <div class="row mb-4 align-items-center bg-light p-2 rounded border mx-0">
                        <label class="col-auto col-form-label fw-bold">ເລືອກປະເພດຜູ້ໃຊ້:</label>
                        <div class="col-md-6">
                            <select class="form-select">
                                <option value="cashier" selected>cashier</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-primary fw-bold w-25">ພາກສ່ວນໃຫ້ເຂົ້າເຖິງ</th>
                                    <th class="text-primary fw-bold">ກຳນົດສິດ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-secondary fw-bold">ຂໍ້ມູນພະນັກງານ</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="bookingSwitch">
                                            <label class="form-check-label text-muted" for="bookingSwitch">ສິດເຂົ້າເຖິງ ແລະ ເບິ່ງຂໍ້ມູນພະນັກງານ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="bookingSwitch">
                                            <label class="form-check-label text-muted" for="bookingSwitch">ສິດເຂົ້າເຖິງ ແລະ ບັນທຶກຂໍ້ມູນພະນັກງານ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="bookingSwitch">
                                            <label class="form-check-label text-muted" for="bookingSwitch">ສິດເຂົ້າເຖິງ ແລະ ແກ້ໄຂ້ຂໍ້ມູນພະນັກງານ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="bookingSwitch">
                                            <label class="form-check-label text-muted" for="bookingSwitch">ສິດເຂົ້າເຖິງ ແລະ ລົບຂໍ້ມູນພະນັກງານ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="bookingSwitch">
                                            <label class="form-check-label text-muted" for="bookingSwitch">ສິດເຂົ້າເຖິງ ແລະ ຈັດການຂໍ້ມູນພະນັກງານທັງໝົດ</label>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-secondary fw-bold">ການເຂົ້າວຽກ-ອອກວຽກ</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="checkinSwitch">
                                            <label class="form-check-label text-muted" for="checkinSwitch">ສິດເຂົ້າເຖິງ ແລະ ເບິ່ງຂໍ້ມູນການມາວຽກ</label>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-secondary fw-bold">ການລາພັກ</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="checkoutSwitch">
                                            <label class="form-check-label text-muted" for="checkoutSwitch">ສິດເຂົ້າເຖິງ ແລະ ເບິ່ງຂໍ້ມູນການລາພັກ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="checkoutSwitch">
                                            <label class="form-check-label text-muted" for="checkoutSwitch">ສິດເຂົ້າເຖິງ ແລະ ອະນຸມັດການລາ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="checkoutSwitch">
                                            <label class="form-check-label text-muted" for="checkoutSwitch">ສິດເຂົ້າເຖິງ ແລະ ປະຕິເສດການລາ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="checkoutSwitch">
                                            <label class="form-check-label text-muted" for="checkoutSwitch">ສິດເຂົ້າເຖິງ ແລະ ຈັດການຂໍ້ມູນການລາທັງໝົດ</label>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-secondary fw-bold">ໃບບິນ ແລະ ການຊຳລະເງິນເດືອນ</td>
                                    <td>
                                        <div class="vstack gap-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="billAccess">
                                                <label class="form-check-label text-muted" for="billAccess">ສິດເຂົ້າເຖິງ ແລະ ແກ້ໄຂໃບບິນ</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="billCancel">
                                                <label class="form-check-label text-muted" for="billCancel">ສິດການ ຍົກເລີກໃບບິນ</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="billCancel">
                                                <label class="form-check-label text-muted" for="billCancel">ສິດເຂົ້າເຖິງ ແລະ ເບິ່ງເງິນເດືອນ</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
  
                                <tr>
                                    <td class="text-secondary fw-bold">ການຕັ້ງຄ່າ</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="posSell">
                                            <label class="form-check-label text-muted" for="posSell">ສິດເຂົ້າເຖິງ ຂໍ້ມູນຂອງບໍລິສັດ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="posSell">
                                            <label class="form-check-label text-muted" for="posSell">ສິດເຂົ້າເຖິງ ການປ່ຽນແປງສະຖານະຕ່າງໆ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="posSell">
                                            <label class="form-check-label text-muted" for="posSell">ສິດເຂົ້າເຖິງ ຈັດການຜູ້ໃຊ້</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="posSell">
                                            <label class="form-check-label text-muted" for="posSell">ສິດເຂົ້າເຖິງ ສິດຂອງຜູ້ໃຊ້</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-secondary fw-bold">ລາຍງານ</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="posSell">
                                            <label class="form-check-label text-muted" for="posSell">ສິດເຂົ້າເຖິງ ລາຍງານປະຫວັດລະບົບ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="posSell">
                                            <label class="form-check-label text-muted" for="posSell">ສິດເຂົ້າເຖິງ ລາຍງານຂໍ້ມູນພະນັກງານ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="posSell">
                                            <label class="form-check-label text-muted" for="posSell">ສິດເຂົ້າເຖິງ ລາຍງານມາວຽກ-ຂາດວຽກ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="posSell">
                                            <label class="form-check-label text-muted" for="posSell">ສິດເຂົ້າເຖິງ ລາຍງານຂໍ້ມູນການລາພັກ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="posSell">
                                            <label class="form-check-label text-muted" for="posSell">ສິດເຂົ້າເຖິງ ລາຍງານຂໍ້ມູນເງິນເດືອນ</label>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
<!-- </div> -->
   
</template>
<script>

export default {
  data() {
    return {
      roles: ["master", "admin", "cashier"],
      selectedRole: "cashier",

      permissions: [
        {
          name: "ຂໍ້ມູນພະນັກງານ",
          items: [
            { key: "employee.view", label: "ເບິ່ງຂໍ້ມູນ", enabled: false },
            { key: "employee.create", label: "ສ້າງຂໍ້ມູນ", enabled: false },
            { key: "employee.edit", label: "ແກ້ໄຂ", enabled: false },
            { key: "employee.delete", label: "ລົບ", enabled: false },
            { key: "employee.manage", label: "ຈັດການທັງໝົດ", enabled: false },
          ],
        },
        {
          name: "ການເຂົ້າວຽກ",
          items: [
            { key: "attendance.view", label: "ເບິ່ງມາວຽກ", enabled: false }
          ]
        },
        {
          name: "ການລາພັກ",
          items: [
            { key: "leave.view", label: "ເບິ່ງການລາ", enabled: false },
            { key: "leave.approve", label: "ອະນຸມັດ", enabled: false },
            { key: "leave.reject", label: "ປະຕິເສດ", enabled: false },
            { key: "leave.manage", label: "ຈັດການທັງໝົດ", enabled: false }
          ]
        }
      ]
    };
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
    
    selectRole(role) {
      this.selectedRole = role;
      // Load permission จาก API ถ้าต้องการ
    },

    updatePermission(perm) {
      console.log("updated:", perm.key, perm.enabled);
    },

    async savePermissions() {
      const dataToSave = {
        role: this.selectedRole,
        permissions: this.permissions
      };

      // ส่งข้อมูลไป backend API
      // await axios.post("/api/roles/update-permission", dataToSave);

      this.$swal({
        icon: "success",
        title: "ອັບເດດຂໍ້ມູນ ສຳເລັດ!",
        text: "ການກຳນົດສິດສຳເລັດແລ້ວ",
        timer: 1500,
        showConfirmButton: false,
      });
    }
  }
};
</script>


<style scoped>
</style>

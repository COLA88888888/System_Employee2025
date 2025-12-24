<template lang="">
    <div class="login-background d-flex justify-content-center align-items-center min-vh-50">
        <div class="authentication-inner py-5 px-4 w-100" style="max-width: 550px;">
            <!-- Registration Card -->
            <div class="card login-card shadow-lg border-0">
                <div class="card-body p-5">
                    <!-- Logo & Title Section -->
                    <div class="text-center mb-5">
                        <h4 class="fw-bold mb-2 text-primary">ຟອມລົງທະບຽນຜູ້ໃຊ້ໃໝ່</h4>
                        <!-- <p class="text-muted fs-5">ຟອມລົງທະບຽນຜູ້ໃຊ້ໃໝ່</p> -->
                        <p class="text-dark fs-6">ກະລຸນາປ້ອນຂໍ້ມູນຂອງທ່ານເພື່ອສ້າງບັນຊີ</p>
                    </div>

                    <!-- Registration -->
                    <div id="formAuthentication" class="mt-4">
                        <!-- Username Field -->
                        <!-- {{username}} -->
                        <div class="mb-4">
                            <label for="Username" class="form-label fs-5 fw-medium mb-2"><i class="bx bx-user me-2"></i>ຊື່ຜູ້ໃຊ້</label>
                            <input type="text" class="form-control form-control-lg py-3" v-model="username" id="Username" name="Username" placeholder="ປ້ອນຊື່ຂອງທ່ານ...">
                        </div>
                        
                        <!-- Email Field -->
                        <div class="mb-4">
                            <label for="email" class="form-label fs-5 fw-medium mb-2"><i class="bx bx-envelope me-2"></i>ອີເມວ</label>
                            <input type="email" class="form-control form-control-lg py-3" v-model="email" id="email" name="email" placeholder="ປ້ອນອີເມວຂອງທ່ານ...">
                        </div>
                        
                        <!-- Password Field -->
                        <div class="mb-4">
                            <label for="password" class="form-label fs-5 fw-medium mb-2"><i class="bx bx-lock me-2"></i>ລະຫັດຜ່ານ</label>
                            <div class="input-group input-group-merge">
                                <input :type="inputPassword1" id="password" class="form-control form-control-lg py-3 border-end-0" v-model="password" name="password" placeholder="ປ້ອນລະຫັດຜ່ານ...">
                                <span @click="inputPassword1=='password'?inputPassword1='text' :inputPassword1='password'" class="input-group-text cursor-pointer">
                                  <i class="icon-base bx bx-hide" v-if="inputPassword1=='password'"></i>
                                  <i class="icon-base bx bx-show " v-if="inputPassword1=='text'"></i>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Confirm Password Field -->
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label fs-5 fw-medium mb-2"><i class="bx bx-lock me-2"></i>ຢືນຢັນລະຫັດຜ່ານ</label>
                            <div class="input-group input-group-merge">
                                <input :type="inputPassword2" id="confirm_password" class="form-control form-control-lg py-3 border-end-0" v-model="confirm_password" name="confirm_password" placeholder="ຢືນຢັນລະຫັດຜ່ານ...">
                                <span @click="inputPassword2=='password'?inputPassword2='text' :inputPassword2='password'" class="input-group-text cursor-pointer">
                                  <i class="icon-base bx bx-hide" v-if="inputPassword2=='password'"></i>
                                  <i class="icon-base bx bx-show " v-if="inputPassword2=='text'"></i>
                                </span>
                            </div>
                        </div>
                        <div class="alert alert-warning" v-if="error_message " role="alert">{{error_message}}</div> <!--ສະແດງ alter-->
                        <div class="mb-4">
                            <button class="btn btn-primary btn-lg d-grid w-100 py-3 fw-bold fs-4" @click="register()">
                                ລົງທະບຽນ
                                <!-- <span v-else><span v-if="!loading"></span>
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    ກຳລັງລົງທະບຽນ...
                                </span> -->
                            </button>
                        </div>
                    </div>

                    <div class="divider d-flex align-items-center my-4">
                        <div class="flex-grow-1 bg-secondary" style="height: 1px;"></div>
                        <span class="px-3 text-muted fs-5">ຫຼື</span>
                        <div class="flex-grow-1 bg-secondary" style="height: 1px;"></div>
                    </div>

                    <p class="text-center mb-0 fs-5">
                        <span>ມີບັນຊີແລ້ວ? </span>
                        <router-link to="/Login" class="text-decoration-none fw-bold fs-5">
                            <span>ເຂົ້າສູ່ລະບົບ</span>
                        </router-link>
                    </p>
                </div>
            </div>
            <!-- /Registration Card -->
        </div>
    </div>
</template>
<script>
export default {
  data() {
    return {
      username: "",
      email: "",
      password: "",
      confirm_password: "",
      error_message: "",
      inputPassword1: "password",
      inputPassword2: "password",
    };
  },

  methods: {
    register() {
      if (
        this.username === "" ||
        this.email === "" ||
        this.password === "" ||
        this.confirm_password === ""
      ) {
        this.error_message = "ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບຖ້ວນ";
        return;
      }

      if (this.password !== this.confirm_password) {
        this.error_message = "ລະຫັດຜ່ານບໍ່ຕົງກັນ";
        return;
      } else {
        this.error_message = "";
      }

      axios
        .post("/api/register", {
          username: this.username,
          email: this.email,
          password: this.password,
        })
        .then((response) => {
          console.log(response.data); // ຕອບກັບຂໍ້ມູນຈາກ API

          this.$router.push("/login"); // ຍ້າຍໄປຫາຫນ້າລົງທະບຽນ
        })
        .catch((error) => {
          console.log(error); // ກໍລະນີ error
        });
    },
  },
};
</script>
<style>
</style>
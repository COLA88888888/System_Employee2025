<template lang="">
    <div class="login-background d-flex justify-content-center align-items-center ">
        <div class="authentication-inner px-4 w-100 " style="max-width: 500px;">
            <!-- Login Card -->
            <div class="card login-card shadow-lg border-0">
                <div class="card-body p-5">
                  <div class="d-flex justify-content-end">
                     <li class="nav-item lh-1 me-3" style="list-style: none;">
                      <div class="dropdown">
                        <i class="bx bx-globe fs-3" role="button" id="langMenu" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                        <ul class="dropdown-menu" aria-labelledby="langMenu">
                          <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                              href="#" @click="$i18n.locale = 'la'">
                              <img :src="url + '/assets/img/Flag/Laos.jpg'" width="20"> ລາວ (LA)
                            </a>
                          </li>

                          <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                              href="#" @click="$i18n.locale = 'en'">
                              <img :src="url + '/assets/img/Flag/USA.png'" width="20"> English (EN)
                            </a>
                          </li>

                          <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                              href="#" @click="$i18n.locale = 'ch'">
                              <img :src="url + '/assets/img/Flag/China.png'" width="20"> 中文 (CH)
                            </a>
                          </li>

                          <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                              href="#" @click="$i18n.locale = 'th'">
                              <img :src="url + '/assets/img/Flag/Thailand.png'" width="20"> ไทย (TH)
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>

                  </div>
                    <!-- Logo & Title Section -->
                    <div class="text-center mb-5">
                        <div class="app-brand justify-content-center mb-4">
                            <div class="company-logo-wrapper d-inline-block position-relative mb-4">
                                <img :src="url + '/assets/img/Company/Logo.png'" width="80" alt="Company Logo" class="company-logo rounded-circle">
                                <!-- <div class="logo-badge bg-success rounded-circle border border-white">
                                    <i class="bx bxs-check-circle text-white"></i>
                                </div> -->
                            </div>
                        </div>
                        <h2 class="fw-bold mb-2 text-primary">{{$t('Employee management system')}}</h2>
                        <p class="text-muted fs-5">{{$t('welcome_message')}}</p>
                        <p class="text-dark fs-6">{{$t('please_login')}}</p>
                    </div>

                    <!-- Login -->
                    <div id="formAuthentication" class="mt-4">
                        <!-- Email Field -->
                        <div class="mb-4">
                            <label for="email" class="form-label fs-5 fw-medium mb-2"><i class="bx bx-envelope me-2"></i>{{$t('email')}}</label>
                            <input type="email" class="form-control form-control-lg py-3" id="email" v-model="email" name="email" :placeholder="$t('enter_email')" autofocus>
                            <!-- <div class="invalid-feedback fs-6" v-if="error_message_email">{{ error_message_email }}</div> -->
                        </div>
                        
                        <!-- Password Field -->
                        <div class="mb-4">
                            <label for="password" class="form-label fs-5 fw-medium mb-2"><i class="bx bx-lock me-2"></i>{{$t('password')}}</label>
                            <div class="input-group input-group-merge">
                                <input :type="inputPassword" id="password" v-model="password" class="form-control form-control-lg py-3 border-end-0" name="password" :placeholder="$t('enter_password')">
                                <span class="input-group-text cursor-pointer bg-transparent border-start-0" @click="inputPassword=='password'?inputPassword='text' :inputPassword='password'">
                                  <i class="icon-base bx bx-hide " v-if="inputPassword=='password'"></i>
                                  <i class="icon-base bx bx-show " v-if="inputPassword=='text'"></i>
                                </span>
                            </div>
                            <!-- <div class="invalid-feedback fs-6" v-if="error_message_password">{{ error_message_password }}</div> -->
                        </div>
                        
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="rememberMe" id="rememberMe">
                                <label class="form-check-label " for="remember-me">{{$t('remember_me')}}</label>
                            </div>
                        </div>
                        <div class="alert alert-warning" v-if="error_message_email || error_message_password || error_message_login " role="alert" >{{error_message_email}}  {{error_message_password}} {{error_message_login}}</div>
                        <div class="mb-4">
                            <button class="btn btn-primary btn-lg d-grid w-100 py-3 fw-bold fs-4" :disabled="ChackLogin" @click="Login()">
                                {{$t('login')}}
                            </button>
                        </div>
                    </div>

                    <!-- <div class="divider d-flex align-items-center my-4">
                        <div class="flex-grow-1 bg-secondary" style="height: 1px;"></div>
                        <span class="px-3 text-muted fs-5">ຫຼື</span>
                        <div class="flex-grow-1 bg-secondary" style="height: 1px;"></div>
                    </div> -->

                    <!-- <p class="text-center mb-0 fs-5">
                        <span>ທ່ານເປັນຜູ້ໃຊ້ໃໝ່? </span>
                        <router-link to="/register" class="text-decoration-none fw-bold fs-5">
                            <span>ລົງທະບຽນ</span>
                        </router-link>
                    </p>  -->

                    <p class="text-center mb-0 fs-5">
                        <span>Management System V1.1.0 </span>
                        <div class="text-decoration-none fw-bold fs-6">
                            <span>Developer By Khola Dve</span>
                        </div>
                    </p>
                </div>
            </div>
            <!-- /Login Card -->
        </div>
    </div>
</template>
<script>
export default {
  data() {
    return {
      url: window.location.origin,
      email: "",
      password: "",
      rememberMe: false,
      error_message_email: "",
      error_message_password: "",
      error_message_login: "",
      inputPassword: "password",
    };
  },

  computed: {
    ChackLogin() {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (this.email) {
        if (emailRegex.test(this.email)) {
          this.error_message_email = "";
        } else {
          this.error_message_email = "ກະລຸນາໃສ່ອີເມວທີ່ຖືກຕ້ອງ";
        }
      } else {
        this.error_message_email = ""; // ພິມແລ້ວເວລາລົບຂໍ້ຄວາມອອກ ແຈ້ງເຕືອນຫາຍໄປ
      }

      // Cehck if password is not empty

      if (this.password) {
        if (this.password.length >= 6) {
          this.error_message_password = "";
        } else {
          this.error_message_password = "ລະຫັດຜ່ານຕ້ອງມີ 6 ຕົວອັກສອນ";
        }
      } else {
        this.error_message_password = ""; // ພິມແລ້ວເວລາລົບຂໍ້ຄວາມອອກ ແຈ້ງເຕືອນຫາຍໄປ
      }

      // check all

      if (
        this.email &&
        this.password &&
        this.error_message_email === "" &&
        this.error_message_password === ""
      ) {
        return false; // Form is valid
      } else {
        return true; // Form is invalid
      }
    },
  },

  methods: {
    Login() {
      axios
        .post("/api/login", {
          email: this.email,
          password: this.password,
          // rememberMe: this.rememberMe,
        })
        .then((response) => {
          console.log(response);

          if (response.data.success) {
            // login ສຳເລັດ
            // set token, user to localStorage
            localStorage.setItem("web_token", response.data.token);
            localStorage.setItem(
              "web_user",
              JSON.stringify(response.data.user)
            );

            // clear form
            this.email = "";
            this.password = "";

            // redirect to dashboard
            this.$router.push("/");
          } else {
            // login ບໍ່ສຳເລັດ
            this.error_message_login = response.data.message;
          }
        })
        .catch((error) => {
          console.log(error);
          this.error_message_login = "ເມວ໌ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ";
        });
    },
  },
};
</script>
<style >
.bx {
  transition: 0.2s;
}

.bx:hover {
  color: #0d6efd;
}

.login-card {
  font-size: 0.9rem; /* ປັບຂະໜາດທັງໜ້າ */
}

.login-card .form-label {
  font-size: 1rem !important; /* label */
}

.login-card input {
  font-size: 0.95rem !important; /* input text */
}

.login-card .btn {
  font-size: 1.1rem !important; /* ປຸ່ມ */
}

.login-card h2 {
  font-size: 1.6rem !important;
}

.login-card p {
  font-size: 0.95rem !important;
}

.dropdown-menu a {
  font-size: 0.9rem !important;
}


</style>
<template lang="">
    <div>
      <!-- Layout wrapper -->
      <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">

          <!-- Menu -->

              <SidebarMenu v-if="staff.getToken" /> <!--ການເອົາ ເມນູຢູ່ຂ້າງມາສະແດງ aside-->

          <!-- / Menu -->

          <!-- Layout container -->
          <div class="layout-page">   

            <!-- Navbar -->
            <nav v-if="staff.getToken" class="layout-navbar container-xxl navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
              <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none ">
                <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                  <i class="icon-base bx bx-menu icon-md" @click="ShowMenu()"></i>
                </a>
              </div>
              <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">

                <!-- Menu nav -->
                <!-- <div class="navbar-nav align-items-center cursor-pointer d-none d-lg-inline-block">
                    <div class="nav-item d-flex align-items-center">
                      <i class="bx bx-menu bx-md"></i>
                    </div>
                  </div> -->
                <!-- /Menu nav -->
  
                <ul class="navbar-nav flex-row align-items-center ms-md-auto">
  
                  <!-- Place this tag where you want the button to render. -->             
                  <!-- User -->
                  <li class="nav-item lh-1 me-3" style="list-style: none;">
                    <div class="dropdown">
                      <!-- Flag Button -->
                      <img :src="currentFlag" width="25" role="button" id="langMenu" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;" />
                      <!-- Dropdown menu -->
                      <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="langMenu">
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

                  <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                      <div class="avatar avatar-online">
                        <img :src="imagePreview" alt="" width="40" class="rounded-circle">
                      </div>
                    </a>
                  </li>
                  <!--/ User -->

                  <!-- Logout -->
                  <div class="nav-item ms-3" style="cursor: pointer;">
                      <i class='bx bx-exit text-danger fs-3' @click="Logout()"></i>
                  </div>

                </ul>
              </div>
            </nav>
            <!-- / Navbar -->
      
            <!-- Content wrapper -->
            <div class="content-wrapper">
              <!-- Content -->
              <div class="container-xxl flex-grow-1 container-p-y content-scroll"> 
                <router-view />
              </div>
              <!-- / Content -->       
   
              <!-- Footer -->
              <footer v-if="staff.getToken" class="content-footer footer bg-light">
                <div class="container-xxl">
                  <!-- Token: {{staff.token}} -->
                  <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                    <div class="mb-2 mb-md-0">
                      &#169;
                        2025 ລະບົບຄຸ້ມຄອງພະນັກງານ
                    </div>
                    <div class="d-none d-lg-inline-block mb-2 mb-md-0">
                          ພັດທະນາໂດຍ: Khola Synontha
                    </div>
                  </div>
                </div>
              </footer>
              <!-- / Footer -->      
              <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
          </div>
          <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle" @click="HideMenu()"></div>
      </div>
      <!-- / Layout wrapper -->
    </div>
</template>
<script>
import { useStaff } from "./Staff/AuthLogin";
export default {
  setup() {
    const staff = useStaff();
    return { staff };
  },

  data() {
    return {
      url: window.location.origin,
      imagePreview: window.location.origin + '/assets/img/Staff_no.png',
    };
  },

  computed: {
  currentFlag() {
    switch (this.$i18n.locale) {
      case "la":
        return this.url + "/assets/img/Flag/Laos.jpg";
      case "en":
        return this.url + "/assets/img/Flag/USA.png";
      case "ch":
        return this.url + "/assets/img/Flag/China.png";
      case "th":
        return this.url + "/assets/img/Flag/Thailand.png";
      default:
        return this.url + "/assets/img/Flag/Laos.jpg";
    }
  }
},

  methods: {
    ShowMenu() {
      document.body.classList.add('layout-navbarfixed','layout-compact','layout-menu-fixed','layout-menu-expanded');
    },

    HideMenu() {
      document.body.classList.remove('layout-navbarfixed','layout-compact','layout-menu-fixed','layout-menu-expanded');
    },

    Logout() {
      this.$swal({
        title: "ເຈົ້າຕ້ອງການອອກຈາກລະບົບ ຫຼື ບໍ່?",
        text: "ກົດຢືນຢັນເພື່ອອອກຈາກລະບົບ!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "ຢືນຢັນ",
        cancelButtonText: "ຍົກເລີກ",
      }).then((result) => {
        if (result.isConfirmed) {
          this.performLogout(); // 👉 เรียกฟังก์ชันจริง
        }
      });
    },

    performLogout() {
      axios
        .get("/api/logout", {
          headers: {
            Authorization: "Bearer " + this.staff.getToken,
          },
        })
        .then((response) => {
          if (response.data.success) {
            localStorage.removeItem("web_token");
            localStorage.removeItem("web-user");
            this.staff.logOut();
            this.$router.push("/login");
          } else {
            console.log("Logout failed");
          }
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
};
</script>
<style>
  .layout-menu{
    transition: transform 0.4s ease-in-out;
  }

  .content-scroll {
    height: calc(100vh - 120px); /* 120px = Navbar + Footer */
    overflow-y: auto;
    overflow-x: hidden;
  }

  .navbar .dropdown-menu {
  right: unset !important;
  left: 0 !important;
  transform: translateX(-100%) !important;
}


</style>  
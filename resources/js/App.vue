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
  
                <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                  <li class="nav-item lh-1 me-3" style="list-style: none;"></li>  
                  <!-- Notifications Dropdown -->
                  <li v-if="permissionStore.role === 'admin' || permissionStore.role === 'Admin'" class="nav-item dropdown lh-1 me-3" style="list-style: none;">
                    <a class="nav-link dropdown-toggle hide-arrow position-relative notification-btn" href="javascript:void(0);" data-bs-toggle="dropdown" @click="fetchNotifications">
                      <div class="icon-wrapper">
                        <i class='bx bx-bell fs-4 pulse-icon'></i>
                        <span v-if="unreadCount > 0" class="badge rounded-pill bg-danger badge-notifications glow-badge" 
                          style="font-size: 0.75rem; padding: 0.25rem 0.45rem; border: 2px solid #fff; position: absolute; top: -8px; right: -8px; background-color: #ff3e1d !important; color: #ffffff !important; font-weight: 800; box-shadow: 0 0 10px rgba(255, 62, 29, 0.5); min-width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; z-index: 100;">
                          {{ unreadCount > 9 ? '9+' : unreadCount }}
                        </span>
                      </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end py-0 notification-dropdown shadow-lg border-0" style="min-width: 360px; overflow: hidden; border-radius: 12px;">
                      <li class="dropdown-header-custom p-3 bg-light border-bottom" style="list-style: none;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                          <h5 class="m-0 fw-bold header-title text-primary">ການແຈ້ງເຕືອນ</h5>
                          <span v-if="unreadCount > 0" class="unread-badge px-2 py-1 bg-label-danger rounded-pill small fw-bold" style="font-size: 0.7rem;">{{ unreadCount }} ໃໝ່</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                           <p class="m-0 text-muted small" style="font-size: 0.75rem;">ຕິດຕາມການເຄື່ອນໄຫວລ່າສຸດຂອງທ່ານ</p>
                           <a v-if="unreadCount > 0" href="javascript:void(0)" class="mark-all-link small text-primary fw-bold" style="text-decoration: none;" @click="markAllAsRead">
                             <i class='bx bx-check-double me-1'></i>
                           </a>
                        </div>
                      </li>
                      
                      <div class="notification-list custom-scrollbar-y" style="max-height: 400px; overflow-y: auto;">
                        <li v-if="notifications.length === 0" class="p-5 text-center empty-state" style="list-style: none;">
                          <div class="empty-icon-bg mb-3 text-muted">
                            <i class='bx bx-bell-off fs-1 opacity-25'></i>
                          </div>
                          <h6 class="text-dark fw-bold mb-1">ບໍ່ມີການແຈ້ງເຕືອນ</h6>
                          <p class="text-muted small px-4 mb-0">ເມື່ອມີການເຄື່ອນໄຫວໃໝ່ ລະບົບຈະແຈ້ງໃຫ້ທ່ານຊາບຢູ່ບ່ອນນີ້</p>
                        </li>
                        
                        <li v-for="note in notifications" :key="note.id" 
                            class="notification-item-new p-3 border-bottom cursor-pointer" 
                            :class="{'unread-style': !note.is_read}"
                            style="list-style: none;"
                            @click="goToNotification(note)">
                          <div class="d-flex align-items-start gap-3 w-100">
                            <div class="flex-shrink-0">
                                <div class="icon-container-box rounded-circle d-flex align-items-center justify-content-center" :class="note.type" style="width: 40px; height: 40px;">
                                  <i :class="getIcon(note.type)" class="fs-5"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                              <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="note-title-text d-block text-truncate" :class="{'fw-bold text-dark': !note.is_read, 'text-muted': note.is_read}">{{ note.title }}</span>
                                <small class="note-time-text text-muted" style="font-size: 0.65rem;">{{ formatTimeShort(note.created_at) }}</small>
                              </div>
                              <p class="m-0 note-description-text text-muted small text-truncate-2" style="font-size: 0.8rem; line-height: 1.4;">{{ note.message }}</p>
                            </div>
                            <div v-if="!note.is_read" class="unread-indicator-dot bg-danger rounded-circle mt-1" style="width: 8px; height: 8px;"></div>
                          </div>
                        </li>
                      </div>

                      <li class="dropdown-footer-custom p-3 text-center border-top bg-light" style="list-style: none;">
                        <router-link to="/notifications" class="view-all-link-btn text-primary fw-bold small" style="text-decoration: none;">
                          ເບິ່ງການແຈ້ງເຕືອນທັງໝົດ <i class='bx bx-chevron-right ms-1'></i>
                        </router-link>
                      </li>
                    </ul>
                  </li>
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
import { usePermissionStore } from "./stores/permission";
export default {
  setup() {
    const staff = useStaff();
    const permissionStore = usePermissionStore();
    return { staff, permissionStore };
  },

  data() {
    return {
      url: window.location.origin,
      imagePreview: window.location.origin + '/assets/img/Staff_no.png',
      notifications: [],
      unreadCount: 0,
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
    },

    fetchUnreadCount() {
      if (!this.staff.getToken) return;
      axios.get("/api/notifications/unread-count", {
        headers: { Authorization: "Bearer " + this.staff.getToken }
      }).then(res => {
        this.unreadCount = res.data.count;
      });
    },

    fetchNotifications() {
      axios.get("/api/notifications?limit=5", {
        headers: { Authorization: "Bearer " + this.staff.getToken }
      }).then(res => {
        this.notifications = res.data.data;
      });
    },

    markAllAsRead() {
      axios.post("/api/notifications/mark-all-read", {}, {
        headers: { Authorization: "Bearer " + this.staff.getToken }
      }).then(() => {
        this.unreadCount = 0;
        this.notifications.forEach(n => n.is_read = true);
      });
    },

    goToNotification(note) {
      if (!note.is_read) {
        axios.post(`/api/notifications/mark-read/${note.id}`, {}, {
          headers: { Authorization: "Bearer " + this.staff.getToken }
        }).then(() => {
          note.is_read = true;
          this.unreadCount = Math.max(0, this.unreadCount - 1);
        });
      }
      if (note.link) {
        this.$router.push(note.link);
      }
    },

    getIcon(type) {
      switch(type) {
        case 'leave': return 'bx bx-calendar-x';
        case 'payroll': return 'bx bx-money';
        case 'attendance': return 'bx bx-time-five';
        default: return 'bx bx-bell';
      }
    },

    formatTime(dateString) {
      if(!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleTimeString('la-LA', { hour: '2-digit', minute: '2-digit' }) + ' ' + 
             date.toLocaleDateString('la-LA');
    },

    formatTimeShort(dateString) {
      if(!dateString) return '';
      const date = new Date(dateString);
      const now = new Date();
      const diff = (now - date) / 1000;

      if (diff < 60) return 'ມື້ກີ້';
      if (diff < 3600) return Math.floor(diff / 60) + ' ນາທີກ່ອນ';
      if (diff < 86400) return Math.floor(diff / 3600) + ' ຊົ່ວໂມງກ່ອນ';
      return date.toLocaleDateString('la-LA');
    }
  },

  mounted() {
    this.fetchUnreadCount();
    // Poll for new notifications every minute
    this.pollInterval = setInterval(this.fetchUnreadCount, 60000);

    // Listen for custom event to refresh notifications immediately
    window.addEventListener('refresh-notifications', this.fetchUnreadCount);
  },

  beforeUnmount() {
    if (this.pollInterval) clearInterval(this.pollInterval);
    window.removeEventListener('refresh-notifications', this.fetchUnreadCount);
  }
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
  right: 0 !important;
  left: auto !important;
  transform: none !important;
}

.icon-wrapper {
  position: relative;
  display: inline-flex;
  padding: 5px;
}

.pulse-icon {
  animation: none;
}

.icon-wrapper:hover .pulse-icon {
  animation: bell-bounce 0.5s ease;
}

@keyframes bell-bounce {
  0%, 100% { transform: rotate(0); }
  25% { transform: rotate(15deg); }
  75% { transform: rotate(-15deg); }
}

.glow-badge {
    animation: badge-pulse 2s infinite;
}

@keyframes badge-pulse {
    0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 62, 29, 0.7); }
    70% { transform: scale(1.1); box-shadow: 0 0 0 6px rgba(255, 62, 29, 0); }
    100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 62, 29, 0); }
}

.notification-item-new {
  transition: all 0.3s ease;
  border-left: 3px solid transparent;
}

.notification-item-new:hover {
  background-color: #f8f9ff !important;
  border-left-color: #696cff;
}

.unread-style {
  background-color: #f4f5ff !important;
}

.icon-container-box.leave { background-color: #ffe5e5; color: #ff3e1d; }
.icon-container-box.payroll { background-color: #e8fadf; color: #71dd37; }
.icon-container-box.attendance { background-color: #e1f0ff; color: #03c3ec; }
.icon-container-box.activity { background-color: #fff2d6; color: #ffab00; }
.icon-container-box.info { background-color: #e7e7ff; color: #696cff; }

.text-truncate-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  line-clamp: 2;
  overflow: hidden;
  white-space: normal;
}

.bg-label-danger {
  background-color: #ffe5e5 !important;
  color: #ff3e1d !important;
}

.custom-scrollbar-y::-webkit-scrollbar {
  width: 5px;
}

.custom-scrollbar-y::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.custom-scrollbar-y::-webkit-scrollbar-thumb {
  background: #d1d1d1;
  border-radius: 10px;
}

.custom-scrollbar-y::-webkit-scrollbar-thumb:hover {
  background: #b1b1b1;
}
</style>
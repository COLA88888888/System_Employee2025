<template>
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="card shadow-sm border-0">
      <div class="card-header d-flex justify-content-between align-items-center bg-white py-3 border-bottom">
        <h5 class="m-0 fw-bold text-primary">
          <i class='bx bx-bell me-2'></i>ການແຈ້ງເຕືອນທັງໝົດ
        </h5>
        <button v-if="unreadCount > 0" class="btn btn-sm btn-outline-primary" @click="markAllAsRead">
          <i class='bx bx-check-double me-1'></i>ໝາຍວ່າອ່ານແລ້ວທັງໝົດ
        </button>
      </div>
      <div class="card-body p-0">
        <div v-if="notifications.length > 0" class="notification-list">
          <div v-for="note in notifications" :key="note.id" 
               class="notification-row p-4 border-bottom d-flex align-items-start gap-3 cursor-pointer"
               :class="{'unread-bg': !note.is_read}"
               @click="goToNotification(note)">
            
            <div class="flex-shrink-0">
              <div class="notif-icon-box rounded-circle d-flex align-items-center justify-content-center" 
                   :class="note.type" style="width: 48px; height: 48px;">
                <i :class="getIcon(note.type)" class="fs-4"></i>
              </div>
            </div>

            <div class="flex-grow-1">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <h6 class="m-0 fw-bold" :class="!note.is_read ? 'text-dark' : 'text-muted'">{{ note.title }}</h6>
                <small class="text-muted">{{ formatTime(note.created_at) }}</small>
              </div>
              <p class="m-0 text-muted" style="line-height: 1.5;">{{ note.message }}</p>
            </div>

            <div v-if="!note.is_read" class="unread-dot bg-danger rounded-circle mt-2" style="width: 10px; height: 10px;"></div>
          </div>
        </div>
        
        <div v-else class="p-5 text-center my-5">
          <div class="mb-3 text-muted opacity-25">
            <i class='bx bx-bell-off' style="font-size: 5rem;"></i>
          </div>
          <h5 class="text-dark fw-bold">ບໍ່ມີການແຈ້ງເຕືອນ</h5>
          <p class="text-muted">ທ່ານບໍ່ມີລາຍການແຈ້ງເຕືອນໃນເວລານີ້</p>
        </div>
      </div>
      <div v-if="pagination.last_page > 1" class="card-footer bg-white border-top py-3">
        <nav aria-label="Page navigation">
          <ul class="pagination justify-content-center m-0">
            <li class="page-item" :class="{disabled: pagination.current_page === 1}">
              <a class="page-link" href="javascript:void(0)" @click="fetchNotifications(pagination.current_page - 1)">
                <i class='bx bx-chevron-left'></i>
              </a>
            </li>
            <li v-for="page in pagination.last_page" :key="page" class="page-item" :class="{active: pagination.current_page === page}">
              <a class="page-link" href="javascript:void(0)" @click="fetchNotifications(page)">{{ page }}</a>
            </li>
            <li class="page-item" :class="{disabled: pagination.current_page === pagination.last_page}">
              <a class="page-link" href="javascript:void(0)" @click="fetchNotifications(pagination.current_page + 1)">
                <i class='bx bx-chevron-right'></i>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
import { useStaff } from '../Staff/AuthLogin';
import axios from 'axios';

export default {
  setup() {
    const staff = useStaff();
    return { staff };
  },
  data() {
    return {
      notifications: [],
      unreadCount: 0,
      pagination: {
        current_page: 1,
        last_page: 1
      }
    };
  },
  methods: {
    fetchNotifications(page = 1) {
      axios.get(`/api/notifications?page=${page}&limit=20`, {
        headers: { Authorization: "Bearer " + this.staff.getToken }
      }).then(res => {
        this.notifications = res.data.data;
        this.pagination.current_page = res.data.current_page;
        this.pagination.last_page = res.data.last_page;
      });
      this.fetchUnreadCount();
    },
    fetchUnreadCount() {
      axios.get("/api/notifications/unread-count", {
        headers: { Authorization: "Bearer " + this.staff.getToken }
      }).then(res => {
        this.unreadCount = res.data.count;
      });
    },
    markAllAsRead() {
      axios.post("/api/notifications/mark-all-read", {}, {
        headers: { Authorization: "Bearer " + this.staff.getToken }
      }).then(() => {
        this.notifications.forEach(n => n.is_read = true);
        this.unreadCount = 0;
        window.dispatchEvent(new CustomEvent('refresh-notifications'));
      });
    },
    goToNotification(note) {
      if (!note.is_read) {
        axios.post(`/api/notifications/mark-read/${note.id}`, {}, {
          headers: { Authorization: "Bearer " + this.staff.getToken }
        }).then(() => {
          note.is_read = true;
          this.unreadCount = Math.max(0, this.unreadCount - 1);
          window.dispatchEvent(new CustomEvent('refresh-notifications'));
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
    }
  },
  mounted() {
    this.fetchNotifications();
  }
};
</script>

<style scoped>
.notification-row {
  transition: all 0.2s ease;
  border-left: 4px solid transparent;
}
.notification-row:hover {
  background-color: #f8f9fa;
  border-left-color: #696cff;
}
.unread-bg {
  background-color: #f4f5ff;
}
.notif-icon-box.leave { background-color: #ffe5e5; color: #ff3e1d; }
.notif-icon-box.payroll { background-color: #e8fadf; color: #71dd37; }
.notif-icon-box.attendance { background-color: #e1f0ff; color: #03c3ec; }
.notif-icon-box.info { background-color: #e7e7ff; color: #696cff; }

/* Custom Scrollbar for list if needed */
.notification-list {
  max-height: 70vh;
  overflow-y: auto;
}
</style>

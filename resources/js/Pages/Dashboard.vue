<template>
  <div>
    <!-- Welcome Banner -->
    <!-- <div class="row">
      <div class="col-12 mb-4">
        <div class="card bg-primary text-white">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h4 class="card-title text-white mb-2">
                  ຍິນດີຕ້ອນຮັບເຂົ້າສູ່ລະບົບຈັດການພະນັກງານ
                </h4>
                <p class="mb-0">
                  ບ່ອນທີ່ທ່ານສາມາດຈັດການຂໍ້ມູນພະນັກງານ, ເງິນເດືອນ, ແລະ
                  ການເຂົ້າ-ອອກວຽກໄດ້ຢ່າງງ່າຍດາຍ
                </p>
              </div>
              <div class="d-none d-sm-block">
                <i class="bx bx-home-alt bx-lg"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> -->

    <!-- Stats Cards -->
    <div class="row">
      <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                <div class="avatar flex-shrink-0 me-3">
                  <span class="avatar-initial rounded bg-label-primary"
                    ><i class="bx bx-group"></i
                  ></span>
                </div>
                <div>
                  <h6 class="mb-1">ພະນັກງານ</h6>
                  <small class="text-muted">ທັງໝົດ</small>
                </div>
              </div>
              <div class="text-end">
                <h5 class="mb-0">1,245</h5>
                <small class="text-success">+12.5%</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                <div class="avatar flex-shrink-0 me-3">
                  <span class="avatar-initial rounded bg-label-success"
                    ><i class="bx bx-time-five"></i
                  ></span>
                </div>
                <div>
                  <h6 class="mb-1">ເຂົ້າວຽກ</h6>
                  <small class="text-muted">ມື້ນີ້</small>
                </div>
              </div>
              <div class="text-end">
                <h5 class="mb-0">120</h5>
                <small class="text-success">+8.2%</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                <div class="avatar flex-shrink-0 me-3">
                  <span class="avatar-initial rounded bg-label-info"
                    ><i class="bx bx-dollar"></i
                  ></span>
                </div>
                <div>
                  <h6 class="mb-1">ເງິນເດືອນ</h6>
                  <small class="text-muted">ເດືອນນີ້</small>
                </div>
              </div>
              <div class="text-end">
                <h5 class="mb-0">8,420,000 ₭</h5>
                <small class="text-danger">-2.1%</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                <div class="avatar flex-shrink-0 me-3">
                  <span class="avatar-initial rounded bg-label-warning"
                    ><i class="bx bx-calendar"></i
                  ></span>
                </div>
                <div>
                  <h6 class="mb-1">ລາພັກ</h6>
                  <small class="text-muted">ຄໍາຮ້ອງໃໝ່</small>
                </div>
              </div>
              <div class="text-end">
                <h5 class="mb-0">12</h5>
                <small class="text-success">+5.3%</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row">
      <div class="col-md-6 col-lg-3 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-info"
                  ><i class="bx bx-calendar-check"></i
                ></span>
              </div>
              <div>
                <h6 class="mb-1">ຄໍາຮ້ອງລາພັກ</h6>
                <small class="text-muted">ທີ່ໄດ້ຮັບການອະນຸມັດ</small>
                <h5 class="mt-2 mb-0">85</h5>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-warning"
                  ><i class="bx bx-calendar-x"></i
                ></span>
              </div>
              <div>
                <h6 class="mb-1">ຄໍາຮ້ອງລາພັກ</h6>
                <small class="text-muted">ທີ່ຖືກປະຕິເສດ</small>
                <h5 class="mt-2 mb-0">15</h5>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-success"
                  ><i class="bx bx-check-circle"></i
                ></span>
              </div>
              <div>
                <h6 class="mb-1">ບັນຊີເງິນເດືອນ</h6>
                <small class="text-muted">ທີ່ຖືກປະມວນຜົນ</small>
                <h5 class="mt-2 mb-0">1,180</h5>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-danger"
                  ><i class="bx bx-error"></i
                ></span>
              </div>
              <div>
                <h6 class="mb-1">ບັນຫາດ້ານເງິນເດືອນ</h6>
                <small class="text-muted">ທີ່ຕ້ອງກວດສອບ</small>
                <h5 class="mt-2 mb-0">7</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts and Tables -->

      <!-- Department Overview -->

      <!-- Recent Attendance -->
      
    <!--/Carts and Tables-->
  </div>
</template>

<script>
export default {
  name: "Dashboard",
  data() {
    return {
      // You can add data properties here if needed for dynamic content
    };
  },
  mounted() {
    // You can add initialization code here if needed
  },
};
</script>

<style scoped>
.card {
  border-radius: 0.5rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  transition: box-shadow 0.3s ease;
}

.card:hover {
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.card-header {
  padding: 1.25rem 1.5rem;
  background-color: transparent;
  border-bottom: 1px solid rgba(0, 0, 0, 0.075);
}

.avatar-initial {
  width: 2.5rem;
  height: 2.5rem;
  font-size: 1.125rem;
}

.bg-label-primary {
  background-color: #e1e2ff !important;
  color: #696cff !important;
}

.bg-label-success {
  background-color: #e8fadf !important;
  color: #71dd37 !important;
}

.bg-label-info {
  background-color: #d7f5fc !important;
  color: #03c3ec !important;
}

.bg-label-warning {
  background-color: #fff2d6 !important;
  color: #ffab00 !important;
}

.bg-label-danger {
  background-color: #ffe0db !important;
  color: #ff3e1d !important;
}

.bg-label-secondary {
  background-color: #ebeef0 !important;
  color: #8592a3 !important;
}

.text-success {
  color: #71dd37 !important;
}

.text-danger {
  color: #ff3e1d !important;
}

.badge {
  font-size: 0.75rem;
  padding: 0.375rem 0.5rem;
}

.bg-primary {
  background-color: #696cff !important;
}

.text-white {
  color: #fff !important;
}

.welcome-banner {
  background: linear-gradient(135deg, #696cff, #8a9bff);
}
</style>
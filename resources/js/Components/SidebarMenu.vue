<template lang="">

        <aside :class="['layout-menu', 'menu-vertical', 'menu', 'bg-menu-theme', { 'collapsed': isMenuCollapsed }]">
  
  <div class="app-brand demo d-flex align-items-center pe-0">
 <router-link to="/dashboard" class="app-brand-link d-flex align-items-center">
  <span class="app-brand-logo demo">
    <img :src="'/assets/img/Company/Logo.png'" alt="Company Logo"
      style="width: 40px; height: 40px; border-radius: 50%;" />
  </span>
  <span class="app-brand-text demo menu-text fw-bold ms-2" 
        :class="{ 'd-none': isMenuCollapsed }"
        style="font-size: 18px;">
    MPY Management
  </span>
</router-link>


  <a href="javascript:void(0);" class="menu-link text-large ms-auto d-flex align-items-center" style="padding: 0; margin: 0;">
  <i :class="isMenuCollapsed ? 'bx bx-chevron-right' : 'bx bx-chevron-left'"
     style="background:#6366F1; color:white; border: 5px solid var(--bs-body-bg);padding:3px; border-radius:50%;"
     @click="toggleMenu()"></i>
</a>

</div>


    <!-- <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="bx bx-chevron-left d-block d-xl-none align-middle" @click="HideMenu()"></i>
    </a> -->
  <div class="menu-divider mt-0  "></div>

  <div class="menu-inner-shadow"></div>

        <ul class="menu-inner fw-bold py-1">
            <li class="menu-item" :class="$route.path=='/dashboard'?'active open':''">
            <router-link to="/dashboard" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home"></i>
                <div class="text-truncate" data-i18n="Dashboards">{{ $t('dashborad') }}</div>
            </router-link>
            </li>

            <!-- <li class="menu-item" :class="$route.path=='/user'?'active open':''">
            <router-link to="/user" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-voice"></i>
                <div class="text-truncate" data-i18n="Dashboards">{{ $t('user') }}</div>
            </router-link>
            </li> -->

            <li class="menu-item" :class="$route.path=='/employee'?'active open':''">
            <router-link to="/employee" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div class="text-truncate" data-i18n="Dashboards">{{ $t('employee') }}</div>
            </router-link>
            </li>

            <li class="menu-item" :class="$route.path=='/department'?'active open':''">
            <router-link to="/department" class="menu-link">
                <i class="menu-icon tf-icons bx bx-building-house"></i>
                <div class="text-truncate" data-i18n="Dashboards">{{ $t('department') }}</div>
            </router-link>
            </li>

            <li class="menu-item" :class="$route.path=='/position'?'active open':''">
            <router-link to="/position" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bookmark-alt"></i>
                <div class="text-truncate" data-i18n="Dashboards">{{ $t('position') }}</div>
            </router-link>
            </li>

            <li class="menu-item" :class="$route.path=='/attendance'?'active open':''">
            <router-link to="/attendance" class="menu-link">
                <i class="menu-icon tf-icons bx bx-timer"></i>
                <div class="text-truncate" data-i18n="Dashboards">{{ $t('attachments') }}</div>
            </router-link>
            </li>

            <li class="menu-item" :class="$route.path=='/break_type'?'active open':''">
            <router-link to="/break_type" class="menu-link">
                <i class="menu-icon tf-icons bx bx-notepad"></i>
                <div class="text-truncate" data-i18n="Dashboards">{{ $t('break Type') }}</div>
            </router-link>
            </li>

            <li class="menu-item" :class="$route.path=='/leave'?'active open':''">
            <router-link to="/leave" class="menu-link">
                <i class="menu-icon tf-icons bx bx-notepad"></i>
                <div class="text-truncate" data-i18n="Dashboards">{{ $t('leave') }}</div>
            </router-link>
            </li>

            <li class="menu-item" :class="$route.path=='/payroll'?'active open':''">
            <router-link to="/payroll" class="menu-link">
                <i class="menu-icon tf-icons bx bx-dollar"></i>
                <div class="text-truncate" data-i18n="Dashboards">{{ $t('payroll') }}</div>
            </router-link>
            </li>

            <li class="menu-item" :class="$route.path=='/report'?'active open':''">
            <router-link to="/report" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                <div class="text-truncate" data-i18n="Dashboards">{{ $t('report') }}</div>
            </router-link>
            </li>
            
            <li class="menu-item" :class="MainMenu">
                 <a href="javascript:void(0);" class="menu-link menu-toggle" @click="MainMenu = MainMenu ? '' : 'open'">
                    <i class="menu-icon icon-base bx bx-cog"></i>                                      
                    <!-- <i class="menu-icon icon-base bx bx-layout"></i> -->
                    <div data-i18n="Layouts">{{$t('settings')}}</div>
                </a>
                <ul class="menu-sub" >
                    <li class="menu-item" :class="SubMenuOpen_1">
                        <router-link to="/user" class="menu-link" @click="SubMenuOpen_1='ative'; SubMenuOpen_2='' ;openSubMenu(1)">
                            <!-- <i class="menu-icon tf-icons bx bx-user-circle"></i> -->
                        <div data-i18n="Collapsed menu">ຈັດການຜູ້ນຳໃຊ້ລະບົບ</div>
                        </router-link>
                    </li>
                    <li class="menu-item" :class="SubMenuOpen_2">
                        <router-link to="/Roles" class="menu-link" @click="SubMenuOpen_1='ative'; SubMenuOpen_2='' ;openSubMenu(2)">
                            <!-- <i class="menu-icon tf-icons bx bx-user-circle"></i> -->
                        <div data-i18n="Collapsed menu">ກຳນົດສິດການເຂົ້າເຖິງ</div>
                        </router-link>
                    </li>
                    <li class="menu-item" :class="SubMenuOpen_3">
                        <router-link to="/company" class="menu-link" @click="SubMenuOpen_1='active'; SubMenuOpen_3='' ;openSubMenu(3)">
                            <!-- <i class="menu-icon tf-icons bx bx-user-circle"></i> -->
                        <div data-i18n="Collapsed menu">ຕັ້ງຄ່າທົ່ວໄປ</div>
                        </router-link>
                    </li>
                </ul>
            </li>
        </ul>

</aside>
</template>
<script>
export default {
data() {
    return {
        isMenuCollapsed: false,
        MainMenu:'',
        MainMenuOpen:'',
        SubMenuOpen_1:'',
        SubMenuOpen_2:'',
        SubMenuOpen_3:'',
        // SubMenuOpen_4:'',
    }
},
    methods: {

      toggleMenu() {
      this.isMenuCollapsed = !this.isMenuCollapsed; // <-- ເປັນ toggle
    },
        openSubMenu(id) {
            // reset menu
            this.SubMenuOpen_1 = '';
            this.SubMenuOpen_2 = '';
            this.SubMenuOpen_3 = '';
            // this.SubMenuOpen_4 = '';

            switch(id){
                case 1:
                    this.SubMenuOpen_1 = 'active';
                    break;
                case 2:
                    this.SubMenuOpen_2 = 'active';
                    break;
                case 3:
                    this.SubMenuOpen_3 = 'active';
                    break;
                // case 4:
                //     this.SubMenuOpen_4 = 'active';
                //     break;
            }
        },

        ShowMenu() {
            document.body.classList.add('layout-navbarfixed','layout-compact','layout-menu-fixed','layout-menu-expanded');
        },

        HideMenu() {
            document.body.classList.remove('layout-navbarfixed','layout-compact','layout-menu-fixed','layout-menu-expanded');
        },
    }
};
</script>
<style scoped>
  .layout-menu{
    transition: transform 0.4s ease-in-out;
  }

/* ຂະໜາດແລະຄວາມຫຼາຍຂອງ sidebar ເມື່ອ collapsed */
.layout-menu.collapsed {
  width: 60px; /* ຄວາມກວ້າງຂອງ sidebar ເລັກເລີຍ, ພຽງພຽງພຽງເພື່ອສະແດງ icon */
  overflow: hidden;
}

/* ເຮັດໃຫ້ຂໍ້ຄວາມຫຼັກໃນ menu ປິ່ນປົວໄວ້ */
.layout-menu.collapsed .menu-inner .text-truncate {
  display: none;
}

/* icon ສາມາດໃຫ້ສະແດງຕາມປົກກະຕິ */
.layout-menu i {
  font-size: 20px;
}

/* ເມື່ອ sidebar collapsed */
.layout-menu.collapsed {
  width: 60px; /* ພຽງເພື່ອໃຫ້ສະແດງແຕ່ icon */
  overflow: hidden;
}

/* hide text ຂອງ menu items ເມື່ອ collapsed */
.layout-menu.collapsed .menu-inner .text-truncate {
  display: none;
}

/* icon ສາມາດສະແດງປົກກະຕິ */
.layout-menu i {
  cursor: pointer;
  font-size: 20px;
}

/* ເຮັດໃຫ້ Sidebar ເລື່ອນໄດ້ */
.layout-menu {
  height: 100vh;       /* ກຳນົດຄວາມສູງໃຫ້ເຕັມໜ້າຈໍ */
  overflow-y: auto;     /* ສ້າງ scrollbar ເມື່ອເນື້ອໃນເຢັນ */
  overflow-x: hidden;   /* ເອົາ scrollbar ແນວນອນອອກ */
}

/* ປັບ scrollbar ໃຫ້ເບິ່ງສວຍ */
.layout-menu::-webkit-scrollbar {
  width: 6px;
}

.layout-menu::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.layout-menu::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 8px;
}

.layout-menu::-webkit-scrollbar-thumb:hover {
  background: #a1a1a1;
}


  
</style>
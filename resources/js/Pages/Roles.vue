<script setup>
import { ref, onMounted } from 'vue';
import { usePermissionStore } from '../stores/permission';
import axios from 'axios';
import Swal from 'sweetalert2';
import { useStaff } from '../Staff/AuthLogin.js';

const permissionStore = usePermissionStore();
const staff = useStaff();
const roles = ref([]);
const currentRole = ref(null);
const isSaving = ref(false);

const fetchRoles = async () => {
    try {
        const res = await axios.get('/api/roles', {
            headers: { Authorization: 'Bearer ' + staff.getToken }
        });
        
        roles.value = res.data.map(r => ({
            ...r,
            permissions: Array.isArray(r.permissions) ? r.permissions : JSON.parse(r.permissions || '[]')
        }));

        if (roles.value.length > 0 && !currentRole.value) {
            currentRole.value = roles.value[0];
        } else if (currentRole.value) {
            const found = roles.value.find(r => r.id === currentRole.value.id);
            if (found) currentRole.value = found;
        }
    } catch (e) {
        console.error(e);
    }
};

const selectRole = (role) => {
    currentRole.value = role;
};

const Addroles = async () => {
    const { value: name } = await Swal.fire({
        title: 'ເພີ່ມປະເພດຜູ້ໃຊ້',
        input: 'text',
        inputLabel: 'ຊື່ປະເພດຜູ້ໃຊ້',
        showCancelButton: true,
        inputValidator: (value) => {
            if (!value) return 'ກະລຸນາໃສ່ຊື່!';
        }
    });

    if (name) {
        try {
            await axios.post('/api/roles', { name, permissions: [] }, {
                headers: { Authorization: 'Bearer ' + staff.getToken }
            });
            Swal.fire('ສຳເລັດ', 'ເພີ່ມຂໍ້ມູນສຳເລັດ', 'success');
            fetchRoles();
        } catch (e) {
            Swal.fire('ຜິດພາດ', 'ເກີດຂໍ້ຜິດພາດ', 'error');
        }
    }
};

const Editroles = async (role) => {
    const { value: name } = await Swal.fire({
        title: 'ແກ້ໄຂປະເພດຜູ້ໃຊ້',
        input: 'text',
        inputLabel: 'ຊື່ປະເພດຜູ້ໃຊ້',
        inputValue: role.name,
        showCancelButton: true,
        inputValidator: (value) => {
            if (!value) return 'ກະລຸນາໃສ່ຊື່!';
        }
    });

    if (name) {
        try {
            await axios.post(`/api/roles/${role.id}`, { name, permissions: role.permissions }, {
                 headers: { Authorization: 'Bearer ' + staff.getToken }
            });
            Swal.fire('ສຳເລັດ', 'ແກ້ໄຂຂໍ້ມູນສຳເລັດ', 'success');
            fetchRoles();
        } catch (e) {
            Swal.fire('ຜິດພາດ', 'ເກີດຂໍ້ຜິດພາດ', 'error');
        }
    }
};

const Deleteroles = async (id) => {
    const result = await Swal.fire({
        title: 'ຢືນຢັນການລົບ?',
        text: "ທ່ານແນ່ໃຈບໍ່ວ່າຈະລົບຂໍ້ມູນນີ້?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ລົບ!',
        cancelButtonText: 'ຍົກເລີກ'
    });

    if (result.isConfirmed) {
        try {
            await axios.delete(`/api/roles/${id}`, {
                 headers: { Authorization: 'Bearer ' + staff.getToken }
            });
            Swal.fire('ລົບແລ້ວ!', 'ຂໍ້ມູນຖືກລົບແລ້ວ.', 'success');
            if (currentRole.value && currentRole.value.id === id) {
                currentRole.value = null;
            }
            fetchRoles();
        } catch (e) {
            Swal.fire('ຜິດພາດ', 'ເກີດຂໍ້ຜິດພາດ', 'error');
        }
    }
};

const savePermissions = async () => {
    if (!currentRole.value) return;
    isSaving.value = true;
    try {
        await axios.post(`/api/roles/${currentRole.value.id}`, { 
            name: currentRole.value.name, 
            permissions: currentRole.value.permissions 
        }, {
            headers: { Authorization: 'Bearer ' + staff.getToken }
        });
        
        Swal.fire({
            icon: 'success',
            title: 'ສຳເລັດ',
            text: 'ບັນທຶກສິດສຳເລັດແລ້ວ',
            timer: 1500,
            showConfirmButton: false
        });
        
        fetchRoles();
    } catch (e) {
        Swal.fire('ຜິດພາດ', 'ບໍ່ສາມາດບັນທຶກໄດ້', 'error');
    } finally {
        isSaving.value = false;
    }
};

const toggleGroup = (groupPermissions) => {
    if (!currentRole.value) return;
    
    // Check if all permissions in the group are already present
    const allPresent = groupPermissions.every(p => currentRole.value.permissions.includes(p));
    
    if (allPresent) {
        // If all are present, remove them (uncheck all)
        currentRole.value.permissions = currentRole.value.permissions.filter(p => !groupPermissions.includes(p));
    } else {
        // Otherwise, add the missing ones (check all)
        groupPermissions.forEach(p => {
            if (!currentRole.value.permissions.includes(p)) {
                currentRole.value.permissions.push(p);
            }
        });
    }
};

onMounted(() => {
    fetchRoles();
});
</script>
<template>
    <div class="row g-4 justify-content-center">     
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h6 class="m-0 fw-bold">ປະເພດຜູ້ໃຊ້</h6>
                    <button type="button" class="btn rounded-pill btn-sm btn-icon btn-light" @click="Addroles()">
                      <i class="bx bx-plus bx-sm cursor-pointer"></i>
                    </button>
                </div>
                <div class="card-body p-0">
                  <div class="list-group list-group-flush">
                      <div v-for="item in roles" :key="item.id" 
                           class="list-group-item d-flex justify-content-between align-items-center py-3 list-group-item-action"
                           :class="{ 'active': currentRole && currentRole.id === item.id }"
                           @click="selectRole(item)"
                           style="cursor: pointer;">
                        <div class="d-flex align-items-center">
                            <span>{{ item.name }}</span>
                            <span class="badge bg-label-primary ms-2" v-if="item.users && item.users.length > 0">
                                {{ item.users.length }}
                            </span>
                        </div>
                        <span>
                            <i class='bx bxs-edit cursor-pointer text-warning me-2' @click.stop="Editroles(item)"></i> 
                            <i class='bx bx-trash cursor-pointer text-danger' @click.stop="Deleteroles(item.id)"></i>
                        </span>
                      </div>
                  </div>
                </div>
            </div>
        </div>

        <div class="col-md-8" v-if="currentRole">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                         <h5 class="m-0">ກຳນົດສິດ: <span class="text-primary">{{ currentRole.name }}</span></h5>
                         <button class="btn btn-primary" @click="savePermissions" :disabled="isSaving">
                            <span v-if="isSaving" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            ບັນທຶກ
                         </button>
                    </div>

                    <div class="mb-4 p-3 bg-light rounded">
                        <label class="form-label fw-bold">ຜູ້ໃຊ້ງານທີ່ມີສິດນີ້:</label>
                        <div v-if="currentRole.users && currentRole.users.length > 0" class="d-flex flex-wrap gap-2">
                            <span v-for="u in currentRole.users" :key="u.id" class="badge bg-white text-primary border border-primary">
                                <i class='bx bx-user me-1'></i> {{ u.name }}
                            </span>
                        </div>
                        <div v-else class="text-muted fst-italic">
                            ຍັງບໍ່ມີຜູ້ໃຊ້ໃນກຸ່ມນີ້
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
                                    <td class="text-secondary fw-bold">
                                        ຂໍ້ມູນພະນັກງານ
                                        <br>
                                        <small class="text-primary cursor-pointer" @click="toggleGroup(['employee.view', 'employee.create', 'employee.edit', 'employee.delete', 'employee.manage'])">
                                            ເລືອກທັງໝົດ
                                        </small>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="employee.view">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ແລະ ເບິ່ງຂໍ້ມູນພະນັກງານ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="employee.create">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ແລະ ບັນທຶກຂໍ້ມູນພະນັກງານ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="employee.edit">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ແລະ ແກ້ໄຂ້ຂໍ້ມູນພະນັກງານ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="employee.delete">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ແລະ ລົບຂໍ້ມູນພະນັກງານ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="employee.manage">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ແລະ ຈັດການຂໍ້ມູນພະນັກງານທັງໝົດ</label>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-secondary fw-bold">
                                        ການເຂົ້າວຽກ-ອອກວຽກ
                                        <br>
                                        <small class="text-primary cursor-pointer" @click="toggleGroup(['attendance.view'])">
                                            ເລືອກທັງໝົດ
                                        </small>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="attendance.view">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ແລະ ເບິ່ງຂໍ້ມູນການມາວຽກ</label>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-secondary fw-bold">
                                        ການລາພັກ
                                        <br>
                                        <small class="text-primary cursor-pointer" @click="toggleGroup(['leave.view', 'leave.approve', 'leave.reject', 'leave.manage'])">
                                            ເລືອກທັງໝົດ
                                        </small>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="leave.view">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ແລະ ເບິ່ງຂໍ້ມູນການລາພັກ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="leave.approve">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ແລະ ອະນຸມັດການລາ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="leave.reject">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ແລະ ປະຕິເສດການລາ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="leave.manage">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ແລະ ຈັດການຂໍ້ມູນການລາທັງໝົດ</label>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-secondary fw-bold">
                                        ໃບບິນ ແລະ ການຊຳລະເງິນເດືອນ
                                        <br>
                                        <small class="text-primary cursor-pointer" @click="toggleGroup(['payroll.edit', 'payroll.cancel', 'payroll.view'])">
                                            ເລືອກທັງໝົດ
                                        </small>
                                    </td>
                                    <td>
                                        <div class="vstack gap-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="payroll.edit">
                                                <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ແລະ ແກ້ໄຂໃບບິນ</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="payroll.cancel">
                                                <label class="form-check-label text-muted">ສິດການ ຍົກເລີກໃບບິນ</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="payroll.view">
                                                <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ແລະ ເບິ່ງເງິນເດືອນ</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
  
                                <tr>
                                    <td class="text-secondary fw-bold">
                                        ການຕັ້ງຄ່າ
                                        <br>
                                        <small class="text-primary cursor-pointer" @click="toggleGroup(['company.view', 'settings.edit', 'user.view', 'user.create', 'user.edit', 'user.delete', 'role.manage'])">
                                            ເລືອກທັງໝົດ
                                        </small>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="company.view">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ຂໍ້ມູນຂອງບໍລິສັດ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="settings.edit">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ການປ່ຽນແປງສະຖານະຕ່າງໆ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="user.view">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ເບິ່ງຂໍ້ມູນຜູ້ໃຊ້</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="user.create">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ສ້າງຜູ້ໃຊ້ໃໝ່</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="user.edit">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ແກ້ໄຂຜູ້ໃຊ້</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="user.delete">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ລົບຜູ້ໃຊ້</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="role.manage">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ສິດຂອງຜູ້ໃຊ້</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-secondary fw-bold">
                                        ລາຍງານ
                                        <br>
                                        <small class="text-primary cursor-pointer" @click="toggleGroup(['report.history', 'report.employee', 'report.attendance', 'report.leave', 'report.payroll'])">
                                            ເລືອກທັງໝົດ
                                        </small>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="report.history">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ລາຍງານປະຫວັດລະບົບ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="report.employee">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ລາຍງານຂໍ້ມູນພະນັກງານ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="report.attendance">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ລາຍງານມາວຽກ-ຂາດວຽກ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="report.leave">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ລາຍງານຂໍ້ມູນການລາພັກ</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" v-model="currentRole.permissions" value="report.payroll">
                                            <label class="form-check-label text-muted">ສິດເຂົ້າເຖິງ ລາຍງານຂໍ້ມູນເງິນເດືອນ</label>
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
</template>
<style>
.cursor-pointer {
    cursor: pointer;
}
.list-group-item.active {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white !important;
}
.list-group-item.active i {
    color: white !important;
}
</style>

import { defineStore } from 'pinia';

export const usePermissionStore = defineStore('permission', {
  state: () => ({
    permissions: [], // ລາຍການ key permission ທີ່ user ມີ
    role: '',
    role_id: null, 
  }),
  actions: {
    setPermissions(perms) {
      this.permissions = perms;
    },
    setRole(role) {
      this.role = role;
    },
    setRoleId(id) {
      this.role_id = id;
    },
    has(key) {
      if (this.role === 'admin' || this.role === 'Admin') return true;
      return this.permissions.includes(key);
    },
  },
});

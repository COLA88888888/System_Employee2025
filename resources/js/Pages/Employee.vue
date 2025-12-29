<template>
    <div class="card">
        <h5 class="card-header fw-bold">{{ $t('employee_report') }}</h5>
        <!-- <div>
        <label class="p-6">ຂະໜາດການສະແດງ:</label>
        <select v-model="zoomLevel" class="form-select w-auto" style="width: 150px; display:inline-block;">
          <option value="1">100%</option>
          <option value="0.95">95%</option>
          <option value="0.9">90%</option>
          <option value="0.85">85%</option>
          <option value="0.8">80%</option>
          <option value="0.75">75%</option>
          <option value="0.7">70%</option>
          <option value="0.65">65%</option>
        </select>
      </div> -->
        <div class="card-body">
            <div class="row" v-if="ShowForm"> <!--ShowForm = false ໃຫ້ປິດຟອມ-->
                <div class="col-md-4 relative d-flex align-items-center justify-content-center">
                    <button type="button" class="btn rounded-pill btn-icon btn-danger delete" v-if="FormEmployee.image" @click="ClearImage()">
                        <span class="icon-base bx bx-x-circle icon-md "></span>
                    </button>
                    <img :src="imagePreview" @click="$refs.imgupload.click()" class="image cursor-pointer" />
                    <input type="file" ref="imgupload" name="" id="image" accept="image/*" class="form-control" style="display:none" @change="OnfileChange">
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="fname" class="form-label fw-bold">{{ $t('first_name') }}:<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control" v-model="FormEmployee.fname" id="fname" :placeholder="$t('enter_first_name')">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold" for="department_employee">{{ $t('position') }}:<span class="text-danger">*</span></label>
                                <select class="form-select" v-model="FormEmployee.position_id" id="department_position">
                                    <option :value="items.id" v-for="items in PositionData" :key="items.id">{{items.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="lname" class="form-label fw-bold">{{ $t('last_name') }}:<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control" v-model="FormEmployee.lname" id="lname" :placeholder="$t('enter_last_name')">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold" for="department_employee">{{ $t('department') }}:<span class="text-danger">*</span></label>
                                <select class="form-select" v-model="FormEmployee.department_id" id="department_position">
                                    <option :value="items.id" v-for="items in DepartmentData" :key="items.id">{{items.name}}</option>
                                </select>
                            </div>
                        </div>    

                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="gender" class="form-label fw-bold">{{ $t('gender') }}:<span class="text-danger"> *</span></label>&nbsp;
                                <input type="radio" v-model="FormEmployee.gender" name="gender" id="gender" value="male"/> ຊາຍ
                                <input type="radio" v-model="FormEmployee.gender" name="gender" id="gender" value="female"/> ຍິງ
                            </div>
                        </div>    
                         
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="lname" class="form-label fw-bold">{{ $t('email') }}:<span class="text-danger"> *</span></label>
                                <input type="email" v-model="FormEmployee.email" class="form-control" id="email" :placeholder="$t('enter_email')">
                            </div>
                        </div>  

                        <div class="col-md-6">
                            <div class="mb-4">
                                <DatePickerLao v-model="FormEmployee.dob" :label="$t('date_of_birth')" dateFormat="Y-m-d" inputId="dob"/>
                            </div>
                        </div>                                           

                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="tel" class="form-label fw-bold">{{ $t('phone') }}:<span class="text-danger"> *</span></label>
                                <input type="text" v-model="FormEmployee.tel" class="form-control" id="tel" :placeholder="$t('enter_phone_number')">
                            </div>
                        </div>

                        <div class="col-12">
                             <div class="mb-4">
                                <label for="lname" class="form-label fw-bold">{{ $t('address') }}:<span class="text-danger"> *</span></label>
                                <textarea class="form-control" v-model="FormEmployee.address" id="address" :placeholder="$t('enter_address')"></textarea> 
                            </div>
                        </div>
                    </div>
                    
                </div>              

                <div class="col-12 text-center">
                    <button type="button" :disabled="IsFormVailb" class="btn btn-primary mb-4 me-2" @click="SaveEmployee()">
                    <span class="icon-base bx bx-download icon-sm me-1"></span>{{ $t('save') }}
                    </button>
                    <button type="button" class="btn btn-danger mb-4" @click="CanCelEmployee()">
                    <span class="icon-base bx bx-revision icon-sm me-1 bx-spin"></span>{{ $t('cancel') }}
                    </button>
                </div>
            </div>

            <div v-else> <!--ShowForm = true ໃຫ້ສະແດງຟອມ-->
                <div class="row">
                    <div class="col-md-6 d-flex mb-2 align-items-center flex-md-nowrap flex-wrap">
                        <span class="me-2 cursor-pointer" @click="Sort = Sort === 'asc' ? 'desc' : 'asc'">
                        <i class="bx bx-sort-down fs-4" v-if="Sort=='desc'"></i>
                        <i class="bx bx-sort-up fs-4" v-if="Sort=='asc'"></i>
                        </span>

                        <select class="form-select w-auto me-2 mb-2" v-model="Perpage">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                        </select>

                        <select class="form-select w-auto me-2 mb-2" v-model="SelectEmployee1">
                            <option value="all">{{ $t('position') }}</option>
                            <option :value="items.id" v-for="items in PositionData" :key="items.id">{{items.name}}</option>
                        </select>

                        <select class="form-select w-auto mb-2" v-model="SelectEmployee2">
                            <option value="all">{{ $t('department') }}</option>
                            <option :value="items.id" v-for="items in DepartmentData" :key="items.id">{{items.name}}</option>
                        </select>

                    </div>

                    <div class="col-lg-6 d-flex mb-2 justify-content-md-end justify-content-end">
                        <div class="input-group w-auto me-2">
                            <input type="text" class="form-control" v-model="Search" :placeholder="$t('search_employee')" @keyup.enter.enter="GetEmployee()"/>
                            <button class="btn btn-primary" type="button" id="button-addon2" @click="GetEmployee()"><i class='bx bx-search-alt-2' ></i></button>
                        </div>
                        <button class="btn btn-success" type="button" @click="AddEmployee()" v-if="$can('employee.create')">{{ $t('add') }}</button>
                    </div>

                </div>
                <!-- <div class="container"> -->
                    <div class="table-responsive text-nowrap">
                    <table class="table table-bordered table-custom">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-center">{{ $t('id') }}</th>
                                <th class="text-center">{{ $t('image') }}</th>
                                <th>{{ $t('first_name') }}</th>
                                <th>{{ $t('last_name') }}</th>
                                <th>{{ $t('department') }}</th>
                                <th>{{ $t('position') }}</th>
                                <!-- <th>ເງິນເດືອນ</th> -->
                                <th>{{ $t('gender') }}</th>
                                <th>{{ $t('dob') }}</th>
                                <th>{{ $t('phone') }}</th>
                                <th>{{ $t('email') }}</th>
                                <th>{{ $t('address') }}</th>
                                <th v-if="$can('employee.edit') || $can('employee.delete')">{{ $t('actions') }}</th>
                            </tr>
                        </thead>
                        <tbody v-if="EmployeeData.data.length > 0">
                            <tr v-for="items in EmployeeData.data" :key="items.id">
                                <td class="text-center">{{items.id}}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="staff-img-wrapper">
                                            <img v-if="items.image" :src="url + '/assets/img/Uploads_Staffs/' + items.image" alt="Staff Image">
                                            <img v-else :src="url + '/assets/img/No-image.jpg'" alt="No Image">
                                        </div>
                                    </div>
                                </td>
                                <td>{{items.fname}}</td>
                                <td>{{items.lname}}</td>
                                <td>{{items.department_name}}</td>
                                <td>{{items.position_name}}</td>
                                <!-- <td>{{items.saraly}}</td> -->
                                <td>{{items.gender}}</td>
                                <td>{{ $formatDate(items.dob) }}</td>
                                <td>{{items.tel}}</td>
                                <td>{{items.email}}</td>
                                <td>{{items.address}}</td>
                                <td class="text-center" v-if="$can('employee.edit') || $can('employee.delete')">
                              <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
                                <div class="dropdown-menu" style="">
                                  <a class="dropdown-item text-warning fw-bold" v-if="$can('employee.edit')" @click="EditEmployee(items.id)" href="javascript:void(0);"><i class="icon-base bx bx-edit-alt me-1"></i>{{ $t('edit') }}</a>
                                  <a class="dropdown-item text-danger fw-bold" v-if="$can('employee.delete')" @click="DeleteEmployee(items.id)" href="javascript:void(0);"><i class="icon-base bx bx-trash me-1"></i>{{ $t('delete') }}</a>
                                </div>
                              </div>
                            </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="15" class="text-center">
                                    <i class="bx bxs-data fs-4"></i>{{ $t('no_data') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Pagination :pagination="EmployeeData" :offset="4" @paginate="GetEmployee($event)" />
                </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
</template>

<script>
import DatePickerLao from '../Components/DatePickerLao.vue';
import {useStaff} from '../Staff/AuthLogin';
export default {
  components: { 
    DatePickerLao,
  },
    setup() {
        const staff = useStaff();
        return {staff};
    },
    data() {
        return {
            // zoomLevel: "1",
            url: window.location.origin,
            imagePreview: window.location.origin + '/assets/img/Staff_no.png',
            Search:'',
            Sort:'asc',
            Perpage:10,
            ShowForm: false,
            FormType: true,
            EditID: "",
            EmployeeData: {
                data:[],
            },
            DepartmentData:[],
            PositionData: [],
            SelectEmployee1: 'all',
            SelectEmployee2: 'all',
            FormEmployee: {
                image:'',
                fname:'',
                lname:'',
                gender:'',
                department_id:'',
                position_id:'',
                dob:'',
                tel:'',
                email:'',
                address:'',
            }
        };
    },

    computed: {
        IsFormVailb() {
            if(this.FormEmployee.fname === '' ||
                this.FormEmployee.lname === '' ||
                this.FormEmployee.gender === ''||
                this.FormEmployee.department_id === '' ||
                this.FormEmployee.position_id === '' ||
                // this.FormEmployee.dob === '' ||
                this.FormEmployee.tel === '' ||
                this.FormEmployee.email === '' ||
                this.FormEmployee.address === ''
            )
            {
                return true;
            }
            else {
                return false;
            }
        },
        
        // ຟັ່ງຊັ່ນຊູມ ສະແດງ :style="tableStyle"
        // tableStyle() {
        //     return {
        //         transform: `scale(${this.zoomLevel})`,
        //         transformOrigin: "top center",
        //         transition: "transform 0.2s ease",
        //     };
        // },
    },

    methods: {
        // ເວລາກົດປຸ່ມ x ໃນຮູບແມ່ນລົບຮູບອອກແລ້ວສະແດງຮູບ Staff_no.png
        ClearImage() {
            this.FormEmployee.image = "";
            this.imagePreview = window.location.origin + "/assets/img/Staff_no.png";
            this.$refs.imageupload.value = ""; // reset imput file image
        },

        OnfileChange(e) {
            const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    this.FormEmployee.image = file; //ບັນທຶກສຳເລັດແລ້ວໃຫ້ຮູບເກັບຢູ່ໃນຟາຍ image
                    reader.onload = (e) => {
                    this.imagePreview = e.target.result; // ຖ້າມີຮູບກໍຈະສະແດງໃນໂຕແປ imagePreview ຮູບພາບທີ່ດຶງມາຈະສະແດງແທນຮູບ Staff_no.png
                    };
                    reader.readAsDataURL(file);
                } else {
                    this.imagePreview = window.location.origin + "/assets/img/Staff_no.png"; // ຖ້າຫາກບໍ່ມີຟາຍກໍຈະສະແດງຮູບStaff_no.png ປົກກະຕິ
                }
                    },

        AddEmployee() {
            // show form
            this.ShowForm = true;
            this.FormType = true;
            this.EditID = "";

            // clear form
            this.FormEmployee = {
                image:'',
                fname:'',
                lname:'',
                gender:'',
                department_id:'',
                position_id:'',
                dob:'',
                tel:'',
                email:'',
                address:'',
            };

                // select first department
                if(this.DepartmentData.length > 0) {
                    this.FormEmployee.department_id = this.DepartmentData[0].id;
                }
                // select first position
                if(this.PositionData.length > 0) {
                    this.FormEmployee.position_id = this.PositionData[0].id;
                }
        },
        

        CanCelEmployee() {
            // hide form
            this.ShowForm = false;
        },

        SaveEmployee() {
            // add employee
            if(this.FormType) {
                axios.post('/api/employee/add', this.FormEmployee , {
                    headers: {
                        "Content-Type":"multipart/form-data" , Authorization: 'Bearer ' + this.staff.getToken,
                    },
                }).then((response) =>{
                    if(response.data.success) {
                        this.$swal({
                            position: "top-end",
                            icon: "success",
                            title: response.data.message, 
                            showConfirmButton: false, 
                            timer: 2500,
                            toast: true, 
                        });
                        this.GetEmployee();
                    }
                    else {
                        this.$swal({
                            icon: "error",
                            text: response.data.message, 
                            title: "ຜິດພາດ", 
                            showConfirmButton: false,
                            timer: 3500, 
                        });
                    }
                    // hide form
                    this.ShowForm = false;
                }).catch((error) => {
                    if (error && error.response && error.response.status === 401) {
                        this.$swal({
                            title: "Token ໝົດອາຍຸແລ້ວ",
                            text: "ກະລຸນາເຂົ້າລະບົບໃໝ່ !",
                            icon: "error",
                        }).then(() => {
                            console.log("Token expired, redirecting to login page.");
                            this.store.logOut();
                            localStorage.removeItem("web_token");
                            localStorage.removeItem("web_user");
                            this.$router.push("/login");
                        });
                    }
                });
                }
                else {
                    // update employee
                    axios.post("/api/employee/update/" + this.EditID, this.FormEmployee, {
                        headers: {
                            "Content-Type":"multipart/form-data" , Authorization: "Bearer " + this.staff.getToken,
                        },
                    }).then((response) => {
                        if (response.data.success) {
                            this.$swal({
                                position: "top-end",
                                icon: "success",
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 2500,
                                toast: true,
                            });
                            this.GetEmployee(); // refresh product list
                        } 
                        else {
                            this.$swal({
                                icon: "error",
                                title: "ຜິດພາດ",
                                text: response.data.message,
                                showConfirmButton: false,
                                timer: 3500,
                            });
                            // this.GetEmployee();
                        }
                        // hide form
                        this.ShowForm = false;
                    })
                    .catch((error) => {
                        if (error && error.response && error.response.status === 401) {
                            this.$swal({
                                title: "Token ໝົດອາຍຸແລ້ວ",
                                text: "ກະລຸນາເຂົ້າລະບົບໃໝ່ !",
                                icon: "error",
                            }).then(() => {
                                console.log("Token expired, redirecting to login page.");
                                this.store.logOut();
                                //clear local Storage
                                localStorage.removeItem("web_token");
                                localStorage.removeItem("web_user");
                                // redirect to login page
                                this.$router.push("/login");
                            });
                        }
                    });
                }
        },

        EditEmployee(id) {
            // edit by id
            this.FormType = false;
            this.EditID = id;
                axios.get("/api/employee/edit/" + id, {
                    headers: {
                        Authorization: "Bearer " + this.staff.getToken,
                    },
                })
                .then((response) => { 
                if (response.data.success) {  
                    this.ShowForm = true;
                    this.FormEmployee = response.data.employee;    
                    // check if image is available
                    if(this.FormEmployee.image) {
                        this.imagePreview = this.url + '/assets/img/Uploads_Staffs/' + this.FormEmployee.image;
                    }        
                    else {
                        this.imagePreview = this.url + '/assets/img/No-image.jpg';
                    }
                }
                else {
                    this.$swal({
                    icon: "error",
                    title: "ຜິດພາດ",
                    text: response.data.message,
                    showConfirmButton: false,
                    timer: 3500,
                    });
                }
                })
                .catch((error) => {
                console.log(error);
                    if (error && error.response && error.response.status === 401) {
                        this.$swal({
                            title: "Token ໝົດອາຍຸແລ້ວ",
                            text: "ກະລຸນາເຂົ້າລະບົບໃໝ່",
                            icon: "error",
                        }).then(() => {
                            console.log("Token expired, redirecting to login page.");
                            this.store.logOut();
                            localStorage.removeItem("web_token");
                            localStorage.removeItem("web_user");
                            this.$router.push("/login");
                        });
                    }
                });
        },

        DeleteEmployee(id) {
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
                    axios.delete("/api/employee/delete/" + id, {
                        headers: {
                            Authorization: "Bearer " + this.staff.getToken,
                        },
                    }).then((response) => {
                        if (response.data.success) {
                            this.GetEmployee(); // refresh category list

                            this.$swal({
                                position: "top-end", // ຕຳແໜ່ງຂອງການແຈ້ງເຕືອນຢູ່ທິ່ນສຸດ
                                icon: "success", //ໄອຄອນສຳເລັດ
                                title: "ລົບຂໍ້ມູນສຳເລັດ", // ຂໍ້ຄວາມຂອງການແຈ້ງເຕືອນ
                                showConfirmButton: false, // ປິດປຸ່ມຢືນຢັນ
                                timer: 2500, //ກຳນົດເວລາໃນການແຈ້ງເຕືອນ
                                toast: true, // ໃຊ້ແບບທອດ
                            });
                        } else {
                            // show error message
                            this.$swal({
                                icon: "error",
                                text: response.data.message,
                                title: "ຜິດພາດ",
                                showConfirmButton: false,
                                timer: 3500,
                            });
                        }
                    }).catch((error) => {
                        console.log(error);
                            if (error && error.response && error.response.status === 401) {
                                this.$swal({
                                    title: "Token ໝົດອາຍຸແລ້ວ",
                                    text: "ກະລຸນາເຂົ້າລະບົບໃໝ່ !",
                                    icon: "error",
                                }).then(() => {
                                    console.log("Token expired, redirecting to login page.");
                                    this.staff.logOut();
                                    localStorage.removeItem("web_token");
                                    localStorage.removeItem("web_user");
                                    this.$router.push("/login");
                                });
                            }
                        });
                }
            });
        },

        GetDepartment() {
            axios.get("/api/department", {
                headers: {
                    Authorization: "Bearer " + this.staff.getToken,
                },
            }).then((response) => {
                this.DepartmentData = response.data;
                }).catch((error) => {
                    if (error && error.response && error.response.status === 401) {
                        console.log("Token expired, redirecting to login page.");
                            // clear token
                            this.staff.logOut();
                            //clear local Storage
                            localStorage.removeItem("web_token");
                            localStorage.removeItem("web_user");
                            // redirect to login page
                            this.$router.push("/login");
                    }
                });
        },
       
        GetPosition() {
            axios.get("/api/position", {
                headers: {
                Authorization: "Bearer " + this.staff.getToken,
                },
            }).then((response) => {
                this.PositionData = response.data;
            }).catch((error) => {
                if (error && error.response && error.response.status === 401) {                
                        console.log("Token expired, redirecting to login page.");
                        // clear token
                        this.staff.logOut();
                        //clear local Storage
                        localStorage.removeItem("web_token");
                        localStorage.removeItem("web_user");
                        // redirect to login page
                        this.$router.push("/login");
                }
          });
        },

        GetEmployee(page = 1) {
            axios.get(`/api/employee?page=${page}&sort=${this.Sort}&perpage=${this.Perpage}&position_id=${this.SelectEmployee1}&department_id=${this.SelectEmployee2}&search=${this.Search}`, {
                headers: {
                     Authorization: "Bearer " + this.staff.getToken,
                },
            })
            .then((response) => {
            this.EmployeeData = response.data;
            })
            .catch((error) => {
            console.log(error); 
                if (error && error.response && error.response.status === 401) {
                    this.$swal({
                    title: "Token ໝົດອາຍຸແລ້ວ",
                    text: "ກະລຸນາເຂົ້າລະບົບໃໝ່ !",
                    icon: "error",
                    }).then(() => {
                    console.log("Token expired, redirecting to login page.");

                    // clear token
                    this.staff.logOut();

                    //clear local Storage
                    localStorage.removeItem("web_token");
                    localStorage.removeItem("web_user");

                    // redirect to login page
                    this.$router.push("/login");
                    });
                }
            });
        }
    },

    created(){
        this.GetDepartment();
        this.GetPosition();
        this.GetEmployee();
    },

    watch: {
        Sort() {
            this.GetEmployee();
        },

        Perpage() {
            this.GetEmployee();
        },

        SelectEmployee1() {
            this.GetEmployee();
        },
        
        SelectEmployee2() {
            this.GetEmployee();
        },

        // ເວລາລົບການຄົ້ນຫາຂໍ້ມູນຈະກັບມາຄືນ
        Search(val) {
            if(val.length === 0) {
                this.GetEmployee();
            }
        }
    }
};
</script>

<style>

/* .td {
  text-align: center;
} */

.table-custom th, 
.table-custom td {
  padding: 12px 16px;
  vertical-align: middle;
  /* border-bottom: 1px solid #e5e7eb; */
  /* font-size: 14px; */
}

.table th,
.table td {
  padding: 10px;
  white-space: nowrap;
  vertical-align: middle;
}

/* Chrome / Edge / Safari */
.table-responsive::-webkit-scrollbar {
  height: 6px; /* ຂະໜາດ scrollbar ນ້ອຍລົງ */
}

.table-responsive::-webkit-scrollbar-thumb {
  background-color: #bbb;
  border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
  background-color: #999;
}

.table-responsive::-webkit-scrollbar-track {
  background-color: #f1f1f1;
}

.staff-img-wrapper {
  width: 70px;
  height: 70px;
  border-radius: 10%;
  overflow: hidden;          /* ຕັດຮູບໃຫ້ເຂົ້າຂອບວົງມົນ */
  /* border: 2px solid #ddd; */
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #fff;
}

.staff-img-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;         /* ຮູບຈະບໍ່ບີດ ແລະເຕັມກອບ */
}

.delete {
    position: absolute;
    right: 20px;
    top:10px;

}

</style>

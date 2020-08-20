<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Employees Table</h3>
                        <div class="card-tools">
                            <button class="btn btn-success" @click="newEmployeeModal">
                                Add New <i class="fas fa-user-plus"></i>
                            </button>
                        </div>
                        <div class="input-group input-group-sm float-right" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search" @keyup="$parent.searchIt" v-model="$parent.search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default" @click="$parent.searchIt"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <th>Employee Type</th>
                                    <th>Employment Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="employee in employees.data" :key="employee.id" v-if="employees.data.length > 0">
                                    
                                    <td>{{ employee.name }}</td>
                                    <td>{{ employee.email }}</td>
                                    <td>{{ employee.department.name }}</td>
                                    <td>{{ employee.employee_type.type }}</td>
                                    <td>{{ employee.employment_type.type }}</td>

                                    <td>
                                        <a href="#" @click="editEmployeeModal(employee)">
                                            <i class="fas fa-edit blue"></i>
                                        </a>
                                        /
                                        <a href="#">
                                            <i class="fas fa-trash red" @click="deleteEmployee(employee.id)"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-center" v-if="employees.data.length == 0">No Entries</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="employees" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="employeeModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="employeeModalCenterTitle" v-show="!isEdit">Add New Employee</h5>
                        <h5 class="modal-title" id="employeeModalCenterTitle" v-show="isEdit">Edit Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="isEdit ? updateEmployee() : createEmployee()">
                        <div class="modal-body">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" placeholder="Enter name" name="name" v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }">
                                        <has-error :form="form" field="name"></has-error>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" placeholder="Enter email" name="email" v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }">
                                        <has-error :form="form" field="email"></has-error>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Employee Department</label>
                                        <select class="form-control" @change="setDepartment($event.target.value)" name="department" :class="{ 'is-invalid': form.errors.has('department') }">
                                            <option value="" selected disabled>Select Department</option>
                                            <option v-for="department in departments" :value="department.id" :key="department.id" :selected="selectedDepartment==department.id">{{ department.name }}</option>
                                        </select>
                                        <has-error :form="form" field="department"></has-error>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Employee type</label>
                                        <select class="form-control" @change="setEmployeeType($event.target.value)" name="employeeType" :class="{ 'is-invalid': form.errors.has('employeeType') }">
                                            <option value="" selected disabled>Select Employee Type</option>
                                            <option v-for="employeeType in employeeTypes" :value="employeeType.id" :key="employeeType.id" :selected="selectedEmployeeType==employeeType.id">{{ employeeType.type }}</option>
                                        </select>
                                        <has-error :form="form" field="employeeType"></has-error>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Employment Type</label>
                                        <select class="form-control" @change="setEmploymentType($event.target.value)" name="employmentType" :class="{ 'is-invalid': form.errors.has('employmentType') }">
                                            <option value="" selected disabled>Select Employment Type</option>
                                            <option v-for="employmentType in employmentTypes" :value="employmentType.id" :key="employmentType.id" :selected="selectedEmploymentType==employmentType.id">{{ employmentType.type }}</option>
                                        </select>
                                        <has-error :form="form" field="employmentType"></has-error>
                                    </div>
                                </div>
                                
                            </div>
                           
                        </div>
                        <div class="modal-footer">

                            <button v-show="!isEdit" type="submit" class="btn btn-primary">Create</button>
                            <button v-show="isEdit" type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            isEdit: false,
            departments: {},
            employeeTypes: {},
            employmentTypes: {},
            employees: {},

            selectedDepartment: '',
            selectedEmployeeType: '',
            selectedEmploymentType: '',

            form: new Form({
                id: '',
                name: '',
                department: '',
                employeeType: '',
                employmentType: '',
                email: '',

            })

           
        }

    },

    methods: {
        newEmployeeModal() {

            this.isEdit=false;
            this.form.reset();
            this.loadDepartments(0);
            this.loadEmployeeTypes(0);
            this.loadEmploymentTypes(0);

            $('#employeeModal').modal('show');

        },

        editEmployeeModal(employee){
            this.isEdit=true;
            this.form.reset();
            this.loadDepartments(employee.department_id);
            this.loadEmployeeTypes(employee.employee_type_id);
            this.loadEmploymentTypes(employee.employment_type_id);
            $('#employeeModal').modal('show');
            // this.form.id=employee.id;
            // this.form.name=employee.name;
            // this.form.email=employee.email;
             this.form.fill(employee);
             this.form.department=employee.department_id;
             this.form.employeeType=employee.employee_type_id;
             this.form.employmentType=employee.employment_type_id;
            


        },

        loadDepartments(id) {
            axios.get("api/departments").then(({ data }) => {
                this.departments = data
                this.selectedDepartment = id
                
            });
        },

        loadEmployeeTypes(id) {
            axios.get("api/employeeTypes").then(({ data }) => {
                this.employeeTypes = data
                this.selectedEmployeeType = id
                
            });
        },

        loadEmploymentTypes(id) {
            axios.get("api/employmentTypes").then(({ data }) => {
                this.employmentTypes = data
                this.selectedEmploymentType = id
            });
        },

        createEmployee() {
            this.$Progress.start();
            this.form.post('api/employee')
                .then(() => {

                    Fire.$emit('afterEvent');

                    $('#employeeModal').modal('hide')
                    toast.fire({
                        icon: 'success',
                        title: 'Employee Created Successfully'
                    })
                    this.$Progress.finish();
                })
                .catch((e) => {
                    if (e.response.status == 403) {
                        swal.fire("forbidden!", "Action is Unauthorized", "error")
                    }
                    this.$Progress.fail();

                })


        },

        updateEmployee(){
            this.$Progress.start();
            this.form.put('api/employee/' + this.form.id)
                .then(() => {
                    this.$Progress.finish();
                    $('#employeeModal').modal('hide')
                    swal.fire(
                        'Updated!',
                        'Employee Information has been Updated.',
                        'success'
                    )
                    Fire.$emit('afterEvent')
                })
                .catch((e) => {
                    if (e.response.status == 403) {
                        swal.fire("Forbidden!", "Action is Unauthorized.", "error");
                    }
                    this.$Progress.fail();
                });
        },
        loadEmployees() {

            axios.get("api/employee").then(({ data }) => (this.employees = data));
        },

        deleteEmployee(id) {

            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                //Send request to the server
                if (result.value) {
                    this.form.delete("api/employee/" + id)
                        .then(() => {
                            swal.fire(
                                'Deleted!',
                                'User has been Deleted.',
                                'success'
                            )
                            Fire.$emit('afterEvent')
                        })
                        .catch((e) => {
                            console.log(e.response);
                            if (e.response.status == 403) {
                                swal.fire("Forbidden!", "Action is Unauthorized.", "error");
                            }
                        });
                }
            })
        },


        getResults(page = 1) {
            axios.get('api/employee?page=' + page)
                .then(response => {
                    this.employees = response.data;
                });
        },

        setDepartment(value){

            this.form.department=value;
        },

        setEmployeeType(value){

            this.form.employeeType=value;
        },

        setEmploymentType(value){

            this.form.employmentType=value;
        },


       

    },



    mounted() {
        console.log('Component mounted.')
    },

    created() {

        Fire.$on('searching', () => {
            let query = this.$parent.search;
            axios.get('api/findEmployee?q=' + query)
                .then((searched) => {
                    this.employees = searched.data;
                })
                .catch(() => {

                });
        });
        this.loadEmployees();
        Fire.$on('afterEvent', () => { this.loadEmployees() });
    },
}

</script>

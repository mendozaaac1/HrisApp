<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Employee Schedule Table</h3>
                        <div class="card-tools">
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="employee in employees.data" :key="employee.id" v-if="employees.data.length > 0">
                                    <td>{{ employee.name }}</td>
                                    <td>{{ employee.email }}</td>
                                    <td>{{ employee.department.name }}</td>
                                    <td>{{ employee.employee_type.type }}</td>
                                    <td>
                                        <!-- <a :href="'/employeeSchedule/logs/'+employee.id">
                                            <i class="fas fa-search-plus primary"></i>
                                        </a> -->
                                        <router-link :to="{ name: 'employeeLogs', params: { id: employee.id }}">
                                            <i class="fas fa-search-plus primary"></i>
                                        </router-link>
                                        /
                                        <a href="#" @click="scheduleModal(employee.id)">
                                            <i class="fas fa-calendar-alt green"></i>
                                        </a>
                                        /
                                         <a href="#" @click="editSchedule(employee)" v-if="employee.schedules.length">

                                            <i class="fas fa-edit blue"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-center" v-if="employees.data.length == 0">No Entries</td>
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
        <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="scheduleModalCenterTitle" v-if="!isEdit">Add Schedules </h5>
                        <h5 class="modal-title" id="scheduleModalCenterTitle" v-if="isEdit">Edit Schedules </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="row mb-4" v-if="!isEdit">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-info addSchedule" @click="addInput"> Add Schedule <i class="fas fa-calendar-alt"></i></button>
                                </div>
                            </div>
                            <div class="row mb-3" v-for="(input, index) in form.inputs">
                                <div class="col-md-6 mb-2">
                                    <div class="input-group">
                                        <input type="text" v-model="input.schedule_name" class="form-control" placeholder="Schedule" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-danger" type="button" @click="isEdit?deleteEmployeeSchedule(input.id):removeInput(index)"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <select name="Day" class="form-control" @change="setDay($event.target.value ,index)">
                                        <option :selected="input.day === 'monday' ? true : false" value="monday" >Monday</option>
                                        <option :selected="input.day === 'tuesday' ? true : false" value="tuesday">Tuesday</option>
                                        <option :selected="input.day === 'wednesday' ? true : false" value="wednesday">Wednesday</option>
                                        <option :selected="input.day === 'thursday' ? true : false" value="thursday">Thursday</option>
                                        <option :selected="input.day === 'friday' ? true : false" value="friday">Friday</option>
                                        <option :selected="input.day === 'saturday' ? true : false" value="saturday">Saturday</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <date-picker v-model="input.from" :lang="lang" type="time" valueType="format" format="hh:mm A" :time-picker-options="{start: '06:00', step: '00:20', end:'23:00'}" placeholder="Schedule start" width="100%"></date-picker>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <date-picker v-model="input.to" :lang="lang" type="time" valueType="format" format="hh:mm A" :time-picker-options="{start: '06:00', step: '00:20', end:'23:00'}" placeholder="Schedule end" width="100%"></date-picker>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" :disabled="form.inputs.length?false:true"  @click.prevent="!isEdit ? scheduleInputs() : updateSchedule()">Save</button>
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

            employees: {},
            isEdit: false,

            form: new Form({

                employee_id: '',
                inputs: [],
        
            }),

            
            lang: {
                days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            }
        }

    },

    methods: {

        loadEmployees() {

            axios.get("api/employee").then(({ data }) => (this.employees = data));
        },

        getResults(page = 1) {
            axios.get('api/employee?page=' + page)
                .then(response => {
                    this.employees = response.data;
                });
        },

        scheduleModal(id) {
            this.isEdit = false;
            this.form.employee_id = id;
            $('#scheduleModal').modal('show');
            this.form.inputs = [];


        },

        addInput() {
            this.form.inputs.push({
                id: '', 
                day: 'monday',
                from: '',
                to: '',
                schedule_name: '',
            })

        },
        removeInput(index) {
            this.form.inputs.splice(index, 1)
        },


        scheduleInputs() {
            this.$Progress.start();
            this.form.post('api/inputSchedules')
                .then((data) => {
                    console.log(data);
                    swal.fire('Success!', 'Schedule added', 'success');
                    Fire.$emit('afterEvent')
                    this.form.inputs = [];
                    $('#scheduleModal').modal('toggle');
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

        },

        setDay(value, index) {
            this.form.inputs[index].day = value;

        },

        editSchedule(employee) {

            this.isEdit = true;
            this.form.employee_id = employee.id;
            this.form.inputs = employee.schedules.map((inputs) => {
                inputs['from'] = this.$moment(inputs['from_formatted']).format("hh:mm A");
                inputs['to'] = this.$moment(inputs['to_formatted']).format("hh:mm A");

                return inputs;
            });
           
            // this.form.inputs = employee.schedules;

            $('#scheduleModal').modal('show');
            
             
        },

        updateSchedule(){

            swal.fire('Success!', 'Schedule updated', 'success');
        },

        deleteEmployeeSchedule(id){
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    this.$Progress.start();
                    axios.get("api/deleteEmployeeSchedule?id="+id+"&emp_id="+this.form.employee_id)
                        .then(({data}) => {
                            console.log(data);
                            this.$Progress.finish();
                            swal.fire('Success!', 'Schedule Deleted.', 'success');
                            Fire.$emit('afterEvent')
                            this.form.inputs = data.map((inputs) => {
                                inputs['from'] = this.$moment(inputs['from_formatted']).format("hh:mm A");
                                inputs['to'] = this.$moment(inputs['to_formatted']).format("hh:mm A");

                                return inputs;
                            });
           
                        })
                }
            });
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

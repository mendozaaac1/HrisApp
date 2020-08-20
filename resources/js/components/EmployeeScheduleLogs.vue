<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-3">
                <div class="card card-primary">
                    <div class="card-body box-profile">
                        <h3 class="profile-username text-center">{{ employee.name }}</h3>
                        <p class="text-muted text-center">{{ employee.department.name }}</p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item" v-for="schedule in employee.schedules" v-if="employee.schedules.length > 0">
                                <b>{{ schedule.schedule_name + ' (' + schedule.day + ')'}}</b> <span class="float-right">{{ schedule.from | formatTime }} - {{ schedule.to | formatTime }}</span>
                            </li>
                            <li class="list-group-item text-center" v-if="employee.schedules.length == 0">
                                <b>No Assigned Schedules</b>
                            </li>
                        </ul>
                        <div class="form-group" v-if="employee.schedules.length">
                            <label for="excel_import">Import Excel Time in/out</label>
                            <input type="file" class="form-control-file" @change="handleImportFile" id="excel_import" ref="excel_import">
                        </div>
                        <a @click.prevent="importExcel" href="#" class="btn btn-primary btn-block" v-if="employee.schedules.length"><b>Import Excel</b></a>
                    </div>
                    <!-- /.card-body -->
                </div>

                <div class="card card-primary">
                    <div class="card-body box-profile">
                        <h3 class="profile-username text-center">Generate Report</h3>
                        <a @click.prevent="importExcel" href="#" class="btn btn-success btn-block" v-if="employee.schedules.length"><b>Export Excel</b></a>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">In/Out</h3>
                        <div class="card-tools">
                        </div>
                        <div class="input-group input-group-sm float-right" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Schedule Name</th>
                                    <th>Day</th>
                                    <th>Date</th>
                                    <th>Time - in</th>
                                    <th>Time - out</th>
                                    <th>Total Hours</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="log in logs.data" v-if="logs.data.length > 0">
                                    <td>{{ log.schedule.schedule_name }}</td>
                                    <td>{{ log.schedule.day | uppercase }}</td>
                                    <td>{{ log.date | formatDate }}</td>
                                    <td>{{ log.time_in | formatTime }}</td>
                                    <td>{{ log.time_out | formatTime }}</td>
                                    <td>{{ log.total_hours }}</td>
                                    <td>
                                        <i class="fas fa-trash red" @click="deleteLog(log.id)"></i>
                                    </td>
                                </tr>
                                <tr v-if="logs.data.length == 0">
                                    <td colspan="7" class="text-center">No Entries</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="logs" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            employee: [],
            logs: {},
            importFile: '',
        }
    },

    methods: {
        loadEmployeeLogs() {
            axios.get("/api/employeeLogs?emp_id=" + this.$route.params.id).then(({ data }) => {
                this.logs = data.logs;
                this.employee = data.employee;
            })
        },

        importExcel() {
            
            let formData = new FormData();
            formData.append('excel', this.importFile);
            formData.append('employee_id', this.employee.id);
            this.$Progress.start();
            axios.post('/api/importExcel',formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(({data}) => {
                console.log(data);
                this.importFile = '';
                this.$refs.excel_import.value = '';
                this.$Progress.finish();
            })
            .catch((response) => {
                this.importFile = '';
                this.$refs.excel_import.value = '';
                this.$Progress.fail();
            });
        },

        handleImportFile()
        {
            this.importFile = this.$refs.excel_import.files[0];   
        },

        getResults(page = 1) {
            axios.get('/api/employeeLogsPaginate?page=' + page + '&emp_id=' + this.$route.params.id)
                .then(response => {
                    this.logs = response.data;
                });
        },
        
        deleteLog(id) {
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
                    axios.get("/api/deleteLoggedSchedule?id=" + id)
                        .then(() => {
                            swal.fire(
                                'Deleted!',
                                'Log has been Deleted.',
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
        }
    },

    mounted() {
        console.log('Component mounted.')
    },

    created() {
        this.loadEmployeeLogs()
        Fire.$on('afterEvent', () => { this.loadEmployeeLogs() });
    },
}

</script>

<style type="text/css">
.widget-user-header {
    background-position: center center;
    background-size: cover;
    height: 250px !important;
}
.profile-image {
    height: 200px !important;
    width: 200px !important;
}
.widget-user-image {
    left: 44.5% !important;
}
</style>
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 mt-3">
                <!-- Widget: user widget style 1 -->
                <div class="card card-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header text-white" style="background-image: url('./img/photo1.png');">
                        <h3 class="widget-user-username text-right">{{ auth.name }}</h3>
                        <h5 class="widget-user-desc text-right">{{ auth.type | uppercase }}</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="profile-image img-circle" :src="getProfilePhoto()" alt="User Avatar">
                    </div>
                   
                </div>
                <!-- /.widget-user -->
            </div>
            <!-- profile -->
            <div class="col-md-12 mt-4">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane" id="activity">
                                DISPLAY ACTIVITY
                            </div>
                            <!-- /.tab-pane -->
                            <!-- /.tab-pane -->
                            <div class="tab-pane active" id="settings">
                                <form class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-12">
                                            <input v-model="form.name" type="text" class="form-control" id="inputName"
                                                placeholder="Name"
                                                :class="{ 'is-invalid': form.errors.has('name') }">
                                            <has-error :form="form" field="name"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 control-label">Email</label>
                                        <div class="col-sm-12">
                                            <input v-model="form.email" type="email" class="form-control" id="inputEmail"
                                                placeholder="Email"
                                                :class="{ 'is-invalid': form.errors.has('email') }">
                                            <has-error :form="form" field="email"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 control-label">Bio</label>
                                        <div class="col-sm-12">
                                            <textarea v-model="form.bio" class="form-control" name="bio" 
                                                id="Bio" placeholder="Bio"
                                                :class="{ 'is-invalid': form.errors.has('bio') }">
                                            </textarea>
                                            <has-error :form="form" field="bio"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="photo" class="col-sm-2 control-label">Profile Photo</label>
                                        <div class="col-sm-12">
                                            <input type="file" @change="updatePhoto" name="photo"
                                                class="form-input" accept="image/png, image/jpeg">
                                            <!-- <label class="custom-file-label" for="customFile">Choose file</label> -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="photo" class="col-sm-12 control-label">Password (leave empty if not changing)</label>
                                        <div class="col-sm-12">
                                            <input v-model="form.password" type="password" name="password" class="form-control"
                                            id="password" placeholder="Password"
                                            :class="{ 'is-invalid': form.errors.has('password') }">
                                            <has-error :form="form" field="password"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-12">
                                            <button @click.prevent="updateInfo" type="submit" class="btn btn-success">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            auth: {
                name: '',
                type: '',
            },
            form: new Form({
                id: '',
                name: '',
                email: '',
                password: '',
                type: '',
                bio: '',
                photo: '',
            })
        }
    },
    methods: {
        getProfilePhoto() {
            if(this.form.photo !== 'profile.png') {
                /*
                no prefix if photo string is base64 else add image profile directory
                 */
                let prefix = ((this.form.photo.length > 200) ? '' : '/img/profile/');
                return prefix+this.form.photo;
            } else {
                return '/img/'+this.form.photo;
            }
        },
        updateInfo() {
            this.$Progress.start();
            this.form.put('api/profile')
            .then(() => {
                swal.fire('Info Updated!', 'Your profile has been updated', 'success');
                // Fire.$emit('updateProfile')
                this.$Progress.finish();
            })
            .catch(() => {
                this.$Progress.fail();
            });
        },
        updatePhoto(e) {
            let file = e.target.files[0];
            console.log(file);
            let reader = new FileReader();

            if (file['size'] < 2111775) {
                reader.onloadend = (file) => {
                    // console.log('RESULT', reader.result);
                    this.form.photo = reader.result;
                }
                reader.readAsDataURL(file);    
            } else {
                swal.fire(
                    'Oops...',
                    'You are uploading a large file (limit 2MB)',
                    'error'
                )
            }
            
        }
    },
    mounted() {
        console.log('Component mounted.')
    },
    created() {
        axios.get('api/profile')
        .then(({data}) => {
            Fire.$on('updateProfile', () => { this.loadUsers() });
            this.form.fill(data);
            this.auth.name = data.name;
            this.auth.type = data.type;
        }); // fill form with data
    }
}

</script>

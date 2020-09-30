<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-8">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3></h3>
                        <p>Employee</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="/employees" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-8">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3></h3>
                        <p>Employee Schedules</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="/employeeSchedule" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-8">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3></h3>
                        <p>Report</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <!-- ./col -->
        </div>
    </div>
</template>
<script>
export default {
    mounted() {
        console.log('Component mounted.')
    }
}

</script>

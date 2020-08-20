<template>
    <div>
        <section class="d-flex justify-content-center mt-5">
            <div class="error-page">
                <h2 class="headline text-primary">404</h2>
                <div class="error-content">
                    <h3><i class="fas fa-exclamation-triangle text-primary"></i> Oops! Page not found.</h3>
                    <p>
                        We could not find the page you were looking for.
                        Meanwhile, you may <a href="/dashboard">return to dashboard</a>
                    </p>
                </div>
                <!-- /.error-content -->
            </div>
            <!-- /.error-page -->
        </section>
    </div>
</template>
<script>
export default {
    mounted() {
        console.log('Component mounted.')
    }
}

</script>

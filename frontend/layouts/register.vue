<template>
    <div>
        <div class="row" >
            <div class="col-md-6 bg-login">

            </div>
            <div class="col-md-5 offset-md-1">
                <div style="text-align: center">
                    <div style="margin-left: auto; margin-right: auto">
                        <nuxt/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        head: {
            bodyAttrs: {
              class: 'hideovy bg-putih'
            }
        },
        data() {
            return {}
        }
    }
</script>

<style>
    .bg-login {
        background: url('../static/assets/img/bg-login.svg') no-repeat top right/cover;
    }
    .hideovy {
        overflow-x: hidden;
    }
</style>

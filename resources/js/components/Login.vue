<template>
    <div class="login">
        <div class="log-in">
            <h1>Log in</h1>

            <form action="/login">
                <div class="form-group">
                    <label for="username">Email or Username</label>
                    <input type="text" id="username" v-model="username" placeholder="you@example.com">
                    <p class="feedback feedback-danger" v-if="errors.has('username')">{{ errors.get('username') }}</p>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" v-model="password" placeholder="*******">
                    <p class="feedback feedback-danger" v-if="errors.has('password')">{{ errors.get('password') }}</p>
                </div>

                <div class="form-group flex items-center tracking-wide responsive">
                    <button @click.prevent="login" class="btn btn-primary" style="border-radius: 9999px; padding-left: 45px; padding-right: 45px;">Login</button>
                    <a class="is-link text-sm ml-auto" href="/password/reset">Forgot Your Password?</a>
                </div>

            </form>
            <slot></slot>
        </div>
    </div>
</template>

<script>
    class Errors
    {
        constructor () {
            this.errors = {};
        }

        records (errors) {
            this.errors = errors;
        }

        has (key) {
            return key in this.errors;
        }

        get (key) {
            return this.errors[key][0];
        }
    }

    export default {
        name: "Login",
        data () {
            return {
                username: '',
                password: '',
                errors: new Errors()
            }
        },
        methods: {
            login () {
                let formData = new FormData();
                formData.append('username', this.username);
                formData.append('password', this.password);

                axios.post('/login', formData).then(res => {
                    if (res.data.status == 200) {
                        window.location.pathname = '/' + res.data.username;
                    } else {
                        this.errors.records({'username': [res.data.message]});
                    }
                }).catch(error => {
                    this.errors.records(error.response.data.errors);
                });
            }
        }
    }
</script>

<style scoped>

</style>

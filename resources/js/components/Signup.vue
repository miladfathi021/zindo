<template>
    <div class="signup">
        <div class="sign-up">
            <h1>Sign Up</h1>

            <form action="/register">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" v-model="name" placeholder="Your Name">
                    <p class="feedback feedback-danger" v-if="errors.has('name')">{{ errors.get('name') }}</p>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input @keyup="checkUsername" type="text" id="username" v-model="username" placeholder="Your Username">
                    <p class="feedback feedback-danger" v-if="errors.has('username')">{{ errors.get('username') }}</p>
                    <p class="feedback feedback-success" v-if="checkUsernameStatus != null">{{ checkUsernameStatus }}</p>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" v-model="email" placeholder="you@example.com">
                    <p class="feedback feedback-danger" v-if="errors.has('email')">{{ errors.get('email') }}</p>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" v-model="password" placeholder="*******">
                    <p class="feedback feedback-danger" v-if="errors.has('password')">{{ errors.get('password') }}</p>
                </div>
                <div class="form-group">
                    <label for="password_confimation">Confirm Password</label>
                    <input type="password" id="password_confimation" v-model="password_confirmation" placeholder="*******">
                </div>

                <div class="form-group flex items-center tracking-wide responsive">
                    <button @click.prevent="signup" class="btn btn-primary" style="border-radius: 9999px; padding-left: 45px; padding-right: 45px;">Sign Up</button>
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
        data () {
            return {
                name: '',
                username: '',
                email: '',
                password: '',
                password_confirmation: '',
                errors: new Errors(),
                checkUsernameStatus: null,
            }
        },
        methods: {
            checkUsername () {
                let formData = new FormData();
                formData.append('username', this.username);

                axios.post('/check/username', formData).then(res => {
                    this.errors = new Errors();
                    this.checkUsernameStatus = res.data.message;
                }).catch(error => {
                    this.checkUsernameStatus = null;
                    this.errors.records(error.response.data.errors);
                });
            },
            signup () {
                let formData = new FormData();
                formData.append('name', this.name);
                formData.append('username', this.username);
                formData.append('email', this.email);
                formData.append('password', this.password);
                formData.append('password_confirmation', this.password_confirmation);

                axios.post('/register', formData).then(res => {
                    return this.$emit('showLoginComponent', res.data.message);
                }).catch(error => {
                    this.errors.records(error.response.data.errors);
                });
            }
        }
    }
</script>

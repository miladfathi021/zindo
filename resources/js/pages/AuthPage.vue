<template>
    <div>
        <div class="logo">
            <h1 :class="logoColor">ZinDo</h1>
        </div>
        <login v-if="showLogin">
            <div class="flex items-center question">
                <p class="text-gray-500">Do not have an account?</p>
                <a class="is-register ml-2 tracking-wide cursor-pointer" @click="showLoginOrRegisterPage">Sign up</a>
            </div>
            <div class="status w-1/2 mx-auto text-center mt-8">
                <p v-if="status != null" class="w-full text-white bg-green-500 px-4 py-2 rounded-full text-sm">{{ status }}</p>
            </div>
        </login>

        <signup @showLoginComponent="showLoginComponent" v-if="showSignup">
            <div class="flex items-center question">
                <p class="text-gray-500">Already have an account?</p>
                <a class="is-register ml-2 tracking-wide cursor-pointer" target="login" @click="showLoginOrRegisterPage">Log in</a>
            </div>
        </signup>

            <div class="secondary-cover"></div>
            <div class="primary-cover" id="cover">
                <div class="img-cover"></div>
                <div class="replace-svg"></div>

                <div class="content">
                    <h2>Welcome to ZinDo</h2>
                    <p>Follow your interests.</p>
                    <p>Hear what people are talking about.</p>
                    <p>Join the conversation.</p>
                    <a @click="showLoginOrRegisterPage" target="login" class="hover-button mt-6 inline-block py-3 px-12 rounded-full border-2 border-white cursor-pointer text-white">Log in</a>
                    <a @click="showLoginOrRegisterPage" class="hover-button ml-3 mt-6 inline-block py-3 px-12 rounded-full border-2 border-white cursor-pointer text-white">Sign up</a>
                </div>
                <div class="circle"></div>
                <div class="circle2"></div>
            </div>
    </div>
</template>

<script>
    import Login from "../components/Login";
    import Signup from "../components/Signup";
    export default {
        name: "AuthPage",
        components: {Signup, Login},
        data () {
            return {
                showLogin: false,
                showSignup: false,
                logoColor: 'text-white',
                status: null,
            }
        },
        methods: {
            showLoginOrRegisterPage (e) {
                if (e.target.target !== 'login') {
                    this.showLogin = false;
                    this.showSignup = true
                } else {
                    this.showLogin = true;
                    this.showSignup = false;
                }

                let primaryCover = document.querySelector('.primary-cover');
                document.querySelector('.secondary-cover').classList.add('get-out');
                primaryCover.classList.add('go-to-right');
                this.logoColor = 'logo-color';
                setTimeout(function () {
                    primaryCover.innerHTML = '';
                    primaryCover.innerHTML = `<div class="new-content">
                                            <h3>Hello, friend</h3>
                                            <p>Watch and share image and video</p>
                                           </div>
                                           <div class="circle"></div>
                                            <div class="circle2"></div>`;
                    document.querySelector('.secondary-cover').classList.remove('secondary-cover');
                },280);

            },
            showLoginComponent (e) {
                this.showLogin = true;
                this.status = e;
            }
        },
    }
</script>

<style scoped>
    .logo-color {
        color: #E90075;
    }
    .status {
        height: 90px;
        overflow: hidden;
        position: relative;
    }
    .status p {
        @apply w-full;
        position: absolute;
        left: 0;
        top: 100%;
        animation: go-to-top 1s;
        animation-fill-mode: forwards;
    }
    @keyframes go-to-top {
        0% {
            top: 100%;
        }
        50% {
            top: 0;
        }
        100% {
            top: 10px;
        }
    }
    .hover-button:hover {
        background-color: #B5005E;
    }
</style>

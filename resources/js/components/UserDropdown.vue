<template>
    <div class="relative">
        <div class="bars" @click="bars">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                 width="30" height="30"
                 viewBox="0 0 24 24"
                 style=" fill:#ffffff;">
                <path
                    d="M 3 5 A 1.0001 1.0001 0 1 0 3 7 L 21 7 A 1.0001 1.0001 0 1 0 21 5 L 3 5 z M 3 11 A 1.0001 1.0001 0 1 0 3 13 L 21 13 A 1.0001 1.0001 0 1 0 21 11 L 3 11 z M 3 17 A 1.0001 1.0001 0 1 0 3 19 L 21 19 A 1.0001 1.0001 0 1 0 21 17 L 3 17 z">

                </path>
            </svg>
        </div>
        <div @mouseover="isMobile ? null : visible = true" @mouseleave="isMobile ? null : visible = false">
            <div class="flex items-center justify-center cursor-pointer py-2 user-login">
                <div class="flex items-center">
                    <img style="width: 40px; height: 40px;" class="rounded-full mr-2 border-2 border-white" :src="user.profile.avatar != null ? '/' + user.profile.avatar : '/images/avatar.svg'">
                    <span class="block text-lg">{{ user.name.length > 20 ? user.name.substr(0, 19) + '...' : user.name }}</span>
                </div>
            </div>
            <div v-if="visible" :class="userDropdown">
                <a href="#" :class="userDropdownItems">Settings</a>
                <hr class="my-0 mx-4">
                <a href="/logout" :class="userDropdownItems">Logout</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "UserDropdown",
        props: ['user'],
        data () {
            return {
                visible: false,
                userDropdown: 'user-dropdown',
                userDropdownItems: 'user-dropdown--items',
            }
        },
        computed: {
            isMobile () {
                return window.matchMedia('(max-width: 460px)').matches;
            },
        },
        methods: {
            bars () {
                this.userDropdown = 'user-dropdown-responsive';
                this.userDropdownItems = 'user-dropdown-responsive--items';
                this.visible = !this.visible;
            }
        }
    }
</script>

<style scoped>

</style>

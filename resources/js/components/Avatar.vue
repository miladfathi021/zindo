<template>
    <div class="edit-avatar rounded-full shadow-lg relative" style="width: 200px; height: 200px;">
        <input type="file" class="hidden" id="avatar" @change="uploadAvatar">
        <img :src="avatarUrl" @mouseover="visible = true" alt="Avatar" class="edit-avatar-img rounded-full border-4 border-white" style="width: 200px; height: 200px;">
        <span
            class="absolute top-0 left-0 w-full h-full border-4 border-white cursor-pointer inline-flex items-center justify-center text-center rounded-full text-2xl text-white"
            style="background-color: rgba(0,0,0, .6);"
            @mouseleave="visible = false"
            v-if="visible"
            @click="openForm"
        >
            <p class="w-12 h-12 border-2 text-center border-white rounded-full" style="line-height: 1.9">+</p>
        </span>
    </div>
</template>

<script>
    export default {
        name: "Avatar",
        props: ['user'],

        data () {
            return {
                person: null,
                visible: false,
            }
        },
        created() {
            this.person = this.user;
        },
        computed: {
            avatarUrl () {
                console.log('/storage/' + this.person.profile.avatar, 'main');
                return this.person.profile.avatar ? '/' + this.person.profile.avatar : '/images/avatar.svg';
            }
        },

        methods: {
            openForm () {
                document.getElementById('avatar').click();
            },
            uploadAvatar (e) {
                let formData = new FormData();
                formData.append('avatar', e.target.files[0]);

                axios.post('/settings/profiles/' + this.person.username + '/avatar', formData).then(res => {
                    if (res.data.status == 201) {
                        this.visible = false;
                        this.person.profile.avatar = res.data.userProfile.avatar;
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <div class="follow-feature">
        <div class="flex items-center" v-if="hasRequestedFollowing">
                <a @click.prevent="showWarningCancelRequest = true" class="btn btn-requested">Requested</a>
                <a v-if="hasRequestedFollower" @click.prevent="accept" class="btn btn-accept btn-2 ml-4">Accept Follow</a>
        </div>

        <div v-else-if="isFollowing" class="flex items-center">
                <a @click.prevent="showWarningUnFollow = true" class="btn btn-unfollow">UnFollow</a>
                <a v-if="hasRequestedFollower" @click.prevent="accept" class="btn btn-accept btn-2 ml-4">Accept Follow</a>
        </div>

        <div class="flex items-center" v-else-if="hasRequestedFollower">
                <a @click.prevent="follow" v-if="!isFollower && !isFollowing && authUser.id != person.id" class="btn btn-follow">Follow</a>
                <a @click.prevent="accept" class="btn btn-accept btn-2 ml-4">Accept Follow</a>
        </div>

        <div class="flex items-center" v-else-if="isFollower && !isFollowing">
                <a @click.prevent="backFollow" class="btn btn-back-follow">Back Follow</a>
        </div>
        <div class="flex items-center" v-else-if="!isFollower && !isFollowing && authUser.id != person.id">
                <a @click.prevent="follow" class="btn btn-follow">Follow</a>
                <a v-if="hasRequestedFollower" @click.prevent="accept" class="btn btn-accept btn-2 ml-4">Accept Follow</a>
        </div>
        <a v-if="authUser.id == person.id" class="btn btn-back-follow" :href="'/settings/profiles/' + authUser.username">Edit Profile</a>

        <div v-if="showWarningCancelRequest">
            <div class="background-full-black">
                <div class="card card-message">
                    <h3 class="text-2xl mb-4">Warning!</h3>
                    <p>Are you sure of the cancellation of your request?</p>
                    <hr class="my-4 border-b border-gray-200">
                    <span @click="requested" class="border border-indigo-600 text-indigo-600 px-6 py-2 inline-block mt-2 rounded cursor-pointer">Yes</span>
                    <span @click="showWarningCancelRequest = false" class="bg-indigo-600 text-white px-6 py-2 inline-block mt-2 rounded cursor-pointer ml-6">Cancel</span>
                </div>
            </div>
        </div>

        <div v-if="showWarningUnFollow">
            <div class="background-full-black">
                <div class="card card-message">
                    <h3 class="text-2xl mb-4">Warning!</h3>
                    <p>Are you sure of the unfollow {{ person.name }}?</p>
                    <hr class="my-4 border-b border-gray-200">
                    <span @click="unfollow" class="border border-indigo-600 text-indigo-600 px-6 py-2 inline-block mt-2 rounded cursor-pointer">Yes</span>
                    <span @click="showWarningUnFollow = false" class="bg-indigo-600 text-white px-6 py-2 inline-block mt-2 rounded cursor-pointer ml-6">Cancel</span>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: ['has_requested_following', 'has_requested_follower', 'is_following', 'is_follower', 'auth', 'user'],
        name: "FollowFeature",
        data () {
            return {
                hasRequestedFollowing: null,
                hasRequestedFollower: null,
                isFollowing: null,
                isFollower: null,
                authUser: [],
                person: [],
                showWarningCancelRequest: false,
                showWarningUnFollow: false,
            }
        },
        created() {
            this.hasRequestedFollowing = this.has_requested_following == 'false' ? false : true;
            this.hasRequestedFollower = this.has_requested_follower == 'false' ? false : true;
            this.isFollowing = this.is_following == 'false' ? false : true;
            this.isFollower = this.is_follower == 'false' ? false : true;
            this.authUser = this.auth;
            this.person = this.user;
        },

        methods: {
            requested () {
                axios.post('/' + this.authUser.username + '/followings/' + this.person.username + '/cancel').then(res => {
                    this.hasRequestedFollowing = false;
                    this.showWarningCancelRequest = false;
                });
            },
            follow () {
                axios.post('/' + this.authUser.username + '/followings/' + this.person.username).then(res => {
                    if (res.data.status == 201) {
                        this.hasRequestedFollowing = true;
                    }
                });
            },
            backFollow () {
                axios.post('/' + this.authUser.username + '/followings/' + this.person.username).then(res => {
                    if (res.data.status == 201) {
                        this.hasRequestedFollowing = true;
                        this.isFollowing = true;
                    }
                });
            },
            accept () {
                axios.post('/' + this.authUser.username + '/followers/' + this.person.username + '/accept').then(res => {
                    if (res.data.status == 200) {
                        this.isFollower = true;
                        this.hasRequestedFollower = false;
                        return this.$emit('add_to_following', 1);
                    }
                });
            },
            unfollow () {
                axios.post('/' + this.authUser.username + '/followings/' + this.person.username + '/unfollow').then(res => {
                    this.hasRequestedFollowing = false;
                    this.isFollowing = false;
                    this.showWarningUnFollow = false;
                    return this.$emit('sub_of_followers', 1);
                });
            },
        }
    }
</script>

<style scoped>

</style>

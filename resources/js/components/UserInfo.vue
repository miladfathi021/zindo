<template>
    <div class="flex items-center justify-center user-info">
        <div class="text-center user-info--items">
            <p class="text-gray-600 user-info--title">Posts</p>
            <p class="text-indigo-500 user-info--value">{{ posts }}</p>
        </div>

        <div class="text-center user-info--items">
            <p class="text-gray-600 user-info--title">Followers</p>
            <p class="text-indigo-500 user-info--value">{{ followers }}</p>
        </div>

        <div class="text-center user-info--items">
            <p class="text-gray-600 user-info--title">Followings</p>
            <p class="text-indigo-500 user-info--value">{{ followings }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "UserInfo",
        props: ['follow_count', 'add_to_followings', 'sub_of_followers', 'add_to_posts'],
        data () {
            return {
                followCount: []
            }
        },
        created() {
            this.followCount = this.follow_count;
        },
        computed: {
            followings () {
                return this.followCount.followings += this.add_to_followings;
            },
            followers () {
                return this.followCount.followers -= this.sub_of_followers;
            },
            posts () {
                return this.followCount.posts += this.add_to_posts;
            },
        }
    }
</script>

<style scoped>

</style>

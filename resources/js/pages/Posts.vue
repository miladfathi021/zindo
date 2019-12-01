<template>
    <div>
        <create-post @attachToPosts="attachToPostEmit" @uploaded="attachToPosts" v-if="auth === user.id" :user="user"></create-post>

        <div class="post-list flex flex-wrap -ml-6 mt-12">
            <div class="posts w-1/3 mb-12" v-for="post of posts">
                <div class="px-6 rounded">
                    <a :href="'/' + user.username + '/posts/' + post.id">
                        <img class="posts-image rounded shadow cursor-pointer" :src="post.image" :alt="user.name">
                    </a>
                </div>
            </div>
        </div>
        <img class="block mx-auto w-32 h-32" v-if="visibleLoading" src="/images/loading.jpg">
    </div>
</template>

<script>
    import CreatePost from "../components/CreatePost";
    export default {
        name: "Posts",
        components: {CreatePost},
        props: ['auth', 'user'],
        data () {
            return {
                posts: [],
                currentPage: 1,
                lastPage: null,
                visibleLoading: false,
            }
        },
        methods: {
            getInitialPosts () {
                axios.get('/' + this.user.username + '/posts').then(res => {
                    this.posts = res.data.posts.data;
                    this.lastPage = res.data.posts.last_page;
                });
            },
            infiniteScroll(post) {
                window.onscroll = () => {
                    let bottomOfWindow =
                        document.documentElement.scrollTop + window.innerHeight ===
                        document.documentElement.offsetHeight;

                    if (bottomOfWindow) {
                        if (this.currentPage != this.lastPage) {
                            this.currentPage++;
                            this.visibleLoading = true;
                            axios
                                .get('/' + this.user.username + '/posts/?page=' + this.currentPage)
                                .then(res => {
                                    this.visibleLoading = false;
                                    for(post of res.data.posts.data) {
                                        this.posts.push(post);
                                    }
                                });
                        }
                    }
                };
            },
            attachToPosts (post) {
                this.posts.unshift(post);
            },
            attachToPostEmit (value) {
                return this.$emit('add_to_posts', value);
            }
        },
        mounted () {
            this.infiniteScroll (this.post);
        },
        beforeMount() {
            this.getInitialPosts();
        }
    }
</script>

<style scoped>

</style>

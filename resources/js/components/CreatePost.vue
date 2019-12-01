<template>
    <div>
        <div class="create-post flex items-center justify-center pt-8">
            <div class="w-1/2 mr-4 relative choose-image">
                <span @click.prevent="openForm" class="input--file flex items-center justify-center block w-full h-64">+</span>
                <div v-if="imageUrl" class="w-full h-full absolute left-0 top-0 flex items-center justify-center rounded">
                    <img :src="imageUrl" class="inline-block w-full h-64 text-6xl rounded shadow-lg cursor-pointer">
                    <p @click.prevent="openForm" class="absolute w-full h-full rounded cursor-pointer flex items-center justify-center text-4xl text-white" style="background-color: rgba(0,0,0, .6);">+</p>
                </div>

                <input type="file" class="hidden" id="input" @change="chooseImage">
            </div>
            <div class="w-1/2 caption-input">
                <textarea name="caption" v-model="caption" class="w-full h-64 bg-white text-gray-800 px-6 py-4 rounded focus:outline-none border border-gray-500 shadow-lg" style="resize: none; vertical-align: middle" placeholder="Write a caption..."></textarea>
            </div>
        </div>
        <div class="mt-6 flex items-center">
            <button @click="createPost" class="btn btn-create mr-12">Create Post</button>
            <div class="flex items-center">
                <p v-if="errors.has('image')" class="feedback feedback-danger">{{ errors.get('image') }}</p>
                <p v-if="errors.has('caption')" class="feedback feedback-danger">{{ errors.get('caption') }}</p>
            </div>
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
        name: "CreatePost",
        props: ['user'],
        data () {
            return {
                image: '',
                imageUrl: null,
                caption: null,
                visible: false,
                errors: new Errors(),
            }
        },
        methods: {
            openForm () {
                document.getElementById('input').click();
            },
            chooseImage (e) {
                this.image = e.target.files[0];
                this.imageUrl = URL.createObjectURL(e.target.files[0]);
            },
            createPost () {
                let formData = new FormData();

                formData.append('image', this.image);
                formData.append('caption', this.caption);

                axios.post('/' + this.user.username + '/posts', formData).then(res => {
                    if (res.data.status == 201) {
                        this.caption = null;
                        this.image = null;
                        this.imageUrl = null;
                        this.errors = new Errors();
                        this.$emit('attachToPosts', 1);
                        return this.$emit('uploaded', res.data.post);
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

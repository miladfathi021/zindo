<template>
    <div class="w-full bg-white p-8 rounded mb-12">
        <h2 class="pb-4 text-gray-600 font-bold tracking-wider uppercase text-xl">Change Your Username</h2>
        <hr class="mb-8">
        <form :action="'/settings/profiles/' + this.user.username + '/username'" method="POST" novalidate>
            <slot></slot>
            <div class="mb-4 -mx-2">
                <div class="update-box-responsive mb-4 w-2/3 px-2">
                    <label class="block text-gray-600 mb-1" for="username">Username</label>
                    <input @keyup="checkUsername" class="w-full bg-gray-200 px-4 py-2 rounded text-gray-700 focus:outline-none" type="text" id="username" name="username" v-model="username">

                    <p class="feedback feedback-success" v-if="checkUsernameStatus != null">{{ checkUsernameStatus }}</p>
                    <p v-if="errors.has('username')" class="feedback feedback-danger">{{ errors.get('username') }}</p>
                </div>
            </div>

            <div>
                <button class="rounded bg-indigo-500 text-white px-4 py-2 hover:bg-indigo-600" type="submit">Save
                    Updates</button>
            </div>
        </form>
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
        name: "UpdateUsername",
        props: ['user'],

        data () {
            return {
                username: this.user.username,
                checkUsernameStatus: null,
                errors: new Errors()
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
            }
        }
    }
</script>

<style scoped>

</style>

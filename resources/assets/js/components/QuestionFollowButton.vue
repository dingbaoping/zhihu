<template>
    <div>
        <button class="btn btn-default" :class="{btn-primary:followed}" v-text="text" @click="follow"></button>
    </div>   
</template>

<script>
    export default {
        props:['question','user'],
        data(){
            return {
                followed:false
            }
        },
        mounted() {
            this.$http.post('/api/question/follower',{'question':this.question,'user':this.user}).then(res=>{
                console.log(res.data);
                this.followed = res.data.followed;
            })
        },
        computed:{
            text(){
                return this.followed ? '已关注' : '关注问题';
            }
        },
        methods:{
            follow(){
                this.$http.post('/api/question/follow',{'question':this.question,'user':this.user}).then(res=>{
                    console.log(res.data);
                    this.followed = res.data.followed;
                })
            }
        }
    }
</script>

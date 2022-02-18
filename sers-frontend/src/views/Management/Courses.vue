<template>
    <div class ="container-fluid" v-if="access">
        <div class="row tabs">
                <p :class="tabs.courseDB? 'active': ''" @click="toggleTab('courseDB')">Course Database</p>
                <p :class="tabs.registration? 'active': ''" @click="toggleTab('registration')" >Registration</p>
                <p :class="tabs.campaign? 'active': ''" @click="toggleTab('campaign')">Voting Campaign</p>
                <!-- <p :class="tabs.proposed? 'active': ''" @click="toggleTab('proposed')" v-if="$store.state.role == 1">Proposed</p> -->
        </div>
        <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.courseDB">
            <CourseDB/>
        </div>
        <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.registration">
            <Registration/>
        </div>
        <div class = "animate__animated animate__fadeIn animate__faster" v-if="tabs.campaign">
            <Campaign/>
        </div>

    </div>
</template>
<script>
    import CourseDB from '../../components/Management/ViewCourses/CourseDB'
    import Campaign from '../../components/Management/ViewCourses/Campaign'
    import Registration from '../../components/Management/ViewCourses/Registration'
    import axios from 'axios'
    
    import {GETUSERROLE_URL} from '../../variables.js'
    import router from '../../router'
    export default{
        data(){
            return {
                tabs:{
                        courseDB: true,
                        registration: false,
                        campaign: false,
                },
                courses: [],
                filtered_courses: [],
                role: 0,
                access : false
            }
        },
        components:{
            CourseDB,
            Campaign,
            Registration,

        },
        methods:{
            toggleTab(tab){
                for (const property in this.tabs){
                    property == tab ? this.tabs[property] = true : this.tabs[property] = false
                }
            },
            checkUserRole(){
                axios.get(GETUSERROLE_URL,
                {withCredentials: true}                
                ).then(response=>{
                    if (response.data == 3){
                        
                        router.push('/instructorcourses')
                    }else if (response.data == 2){
                        
                        router.push('/')
                    }else if (response.data != 4){
                        router.push('/courses')
                    }else{
                        
                        this.access = true
                    }
                })
            },

        },
        mounted(){
            this.checkUserRole()
        }
        

    }
</script>
<style scoped>
</style>
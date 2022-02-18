<template>
    <div class ="container-fluid display-container" v-if = "access">
        
        <div v-if="table">
            <InstructorTable/>
        </div>
        <div v-if="individual">
            <IndividualInstructor :instructor="selectedInstructor"/>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import {GETUSERROLE_URL} from '../variables'
    import Swal from 'sweetalert2'
    import router from '../router.js'
    import InstructorTable from '../components/InstructorDB/InstructorTable'
    import IndividualInstructor from '../components/InstructorDB/IndividualInstructor'
    export default {
        name: "InstructorDatabase",
        data(){
            return{
                table:true,
                individual: false,
                instructorDB: [],
                groupedByInstructor: {},
                selectedInstructor: {},
                access: false
            }
        },
        components:{
            InstructorTable,
            IndividualInstructor
        },
        methods:{
            // getInstructorDatabase(){
            //     axios.get(GETINSTRUCTORDATABASE_URL,
            //     {withCredentials: true}
            //     ).then(response=>{
            //         console.log(response.data)
            //         this.instructorDB = response.data
            //         this.groupInstructorDB()
            //     })
            // },
            // groupInstructorDB(){
            //     this.instructorDB.forEach(this.arrangeByInstructor)
                
                
            // },
            // arrangeByInstructor(value){
                
            //     if (! Object.keys(this.groupedByInstructor).includes(String(Object.values(value)[0]))){
            //         this.groupedByInstructor[value.Instructor_ID] = [value]
            //     }else{
            //         this.groupedByInstructor[value.Instructor_ID].push(value)
            //     }

                
            // },
            checkUserRole(){
                axios.get(GETUSERROLE_URL,
                {withCredentials: true}                
                ).then(response=>{
                    
                    if (response.data != 2 && response.data != 4){
                        this.redirectToCourse()
                        

                    }else{
                        this.access = true
                        
                    }
                })
            },
            redirectToCourse(){
                let timerInterval
                Swal.fire({
                icon: 'error',
                title: 'Access denied!',
                html: 'You do not have the access rights! Redirecting...',
                timer: 1000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
                })
                router.push('/courses')
            },
        },
        mounted(){
            this.checkUserRole()
        }
    }
</script>
<style scoped>

    .table tr:hover .internal-table{
        color:white;
    }

    .internal-table td, tr{
        width: 100%;
        border: none;
        border-bottom: 0.7px solid rgba(0,0,0,0.1);
        
    }

    .internal-table{
        pointer-events: none;
    }
    
</style>
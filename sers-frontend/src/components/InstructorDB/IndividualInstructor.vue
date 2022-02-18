<template>
    <div>
        <span class='back'>
            <button type='button' @click="goBack()"><i class="fas fa-long-arrow-alt-left"></i></button>
        </span>
        <div class="container-fluid">
            <div class="row">
                <h4>Instructor Details</h4>
            </div>
            <div class="row my-4">
                <div class="col-6">
                    <table>
                        <tr>
                            <th>Instructor: </th>
                            <td>{{instructor[0].User_Name}}</td>
                        </tr>
                        <tr>
                            <th>Organisation: </th>
                            <td>{{instructor[0].Company}} - {{instructor[0].Designation}}</td>
                        </tr>
  
                    </table>
                </div>
                <div class="col-6">
                    <table>
                        <tr>
                            <th><i class="fas fa-phone-square-alt"></i> Number: </th>
                            <td>{{instructor[0].Contact ? instructor[0].Contact: 'N.A.'}}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-envelope"></i> Email: </th>
                            <td>{{instructor[0].User_Email}}</td>
                        </tr>
  
                    </table>
                </div>
            </div>
        </div>
        <div class = "table-container animate__animated animate__fadeIn animate__faster">
            <div class="table-header">
                <h4>Courses Taught</h4>
            </div>
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">#</th>
                        <th scope = "col">Course</th>
                        <th scope = "col">Course Description</th>
                        <th scope = "col"># Classes Conducted</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(course, idx) in instructor" :key="idx">
                        <td>{{idx + 1}}</td>
                        <td>{{course.Course_Name}}</td>
                        <td>{{course.Course_Desc}}</td>
                        <td>{{course.No_Conducted}}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>
<script>

    export default {
        name: "IndividualInstructor",
        data(){
            return{
            }
        },
        components:{
        
        },
        props: {
            instructor: Object
        },
        methods:{
            goBack(){
                this.$parent.table = true
                this.$parent.individual = false
            }
        },
        mounted(){
          
        }
    }
</script>
<style scoped>
    .back button{
        background-color: var(--navigation-color);
        border: none;
        font-size: 2em;
        transition:0.2s;
    }
    .back button:hover{
        color: var(--button-color);
    }
    .back button:focus{
        outline:none;
        box-shadow:none;
    }
    
</style>
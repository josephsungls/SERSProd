<template>
    <div>
        <div class="view-select">
            <b>View by: </b> 
            <select v-model="selectView">
                <option>Instructor</option>
                <option>Course</option>
            </select>
        </div>
        <div class = "table-container animate__animated animate__fadeIn animate__faster">
            <div class="table-header">
                <h4>Instructor Database</h4>
                <p class = "text-muted mb-5">Find suitable instructors and industry partners for courses</p>
            </div>
            <table class = "table">
                <thead v-if="selectView == 'Instructor'">
                    <tr>
                        <th scope = "col">Instructor</th>
                        <th scope = "col">Instructor Email</th>
                        <th scope = "col">Organisation</th>
                        <th scope = "col">Course Taught</th>
                    </tr>
                </thead>
                <thead v-if="selectView == 'Course'">
                    <tr>
                        <th scope = "col">Course Name</th>
                        <th scope = "col">Instructor </th>
                        <th scope = "col">Instructor Email</th>
                        <th scope = "col">Organisation</th>
                        <th scope = "col"></th>
                        
                    </tr>
                </thead>

                <tbody v-if="selectView == 'Instructor'">
                    <tr v-for="instructor in Object.keys(groupedByInstructor)" :key="instructor" @click="selectInstructor(groupedByInstructor[instructor])">
                        
                        <td><b>{{instructor}}</b> <br> {{groupedByInstructor[instructor][0].User_Name}}</td>
                        <td>{{groupedByInstructor[instructor][0].User_Email}}</td>
                        <td>{{groupedByInstructor[instructor][0].Company? groupedByInstructor[instructor][0].Company + " - "+ groupedByInstructor[instructor][0].Designation : 'NIL'}}</td>
                        <td class ="internal-td">
                            <table class = "table internal-table" >
                                
                                    <tr v-for="(course, idx) in groupedByInstructor[instructor]" :key="idx" :class="idx != (groupedByInstructor[instructor].length - 1 )? 'btm-border': ''">
                                        
                                        <td>{{course.Course_Name}}</td>
                                    </tr>
                                
                            </table>
                        </td>
                        
                    </tr>
                </tbody>
                <tbody v-if="selectView == 'Course'">
                    <tr v-for="course_id in Object.keys(groupedByCourse)" :key="course_id">
                        <td><b>{{course_id}}</b> <br/> {{groupedByCourse[course_id][0].Course_Name}}</td>
                        <td class ="internal-td">
                            <table class = "table internal-table" >
                                <tr v-for="(instructor, idx) in groupedByCourse[course_id]" :key="idx" :class="[idx != (groupedByCourse[course_id].length - 1 )? 'btm-border': '']" >
                                   
                                    <td><b>{{instructor.Instructor_ID}}</b> <br/> {{instructor.User_Name}}</td>
                                    
                                </tr>
                            </table>
                        </td>
                        <td class ="internal-td">
                            <table class = "table internal-table">
                                <tr v-for="(instructor, idx) in groupedByCourse[course_id]" :key="idx" :class="idx != (groupedByCourse[course_id].length - 1 )? 'btm-border': ''">
                                   
                                    <td><br/>{{instructor.User_Email}}</td>
                                    
                                </tr>
                            </table>
                        </td>                                               
                        <td class ="internal-td">
                            <table class = "table internal-table">
                                <tr v-for="(instructor, idx) in groupedByCourse[course_id]" :key="idx" :class="idx != (groupedByCourse[course_id].length - 1 )? 'btm-border': ''" >
                                   
                                    <td><br/>{{instructor.Company? instructor.Company : 'NIL'}}</td>
                                    
                                </tr>
                            </table>
                        </td>
                        

                        <td class ="internal-td">
                            <table class = "table internal-table2">
                                <tr v-for="(instructor, idx) in groupedByCourse[course_id]" :key="idx" :class="idx != (groupedByCourse[course_id].length - 1 )? 'btm-border': ''" >
                                   
                                    <td class ="eye" @click="selectInstructor(groupedByInstructor[instructor.Instructor_ID])">
                                        <br/>
                                        
                                        <i class="fas fa-eye"></i>
                                    </td>
                                    
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>
<script>
    import axios from 'axios'
    import {GETINSTRUCTORDATABASE_URL} from '../../variables'
    export default {
        name: "InstructorTable",
        data(){
            return{
                instructorDB: [],
                groupedByInstructor: {},
                groupedByCourse: {},
                selectView: 'Instructor'
            }
        },
        methods:{
            getInstructorDatabase(){
                axios.get(GETINSTRUCTORDATABASE_URL,
                {withCredentials: true}
                ).then(response=>{
                    this.instructorDB = response.data
                    this.groupInstructorDB()
                })
            },
            groupInstructorDB(){
                this.instructorDB.forEach(this.arrangeByInstructor)
                this.instructorDB.forEach(this.arrangeByCourse)
                
            },
            arrangeByInstructor(value){
                
                if (! Object.keys(this.groupedByInstructor).includes(String(Object.values(value)[0]))){
                    this.groupedByInstructor[value.Instructor_ID] = [value]
                }else{
                    this.groupedByInstructor[value.Instructor_ID].push(value)
                }

                
            },
            arrangeByCourse(value){

                if (! Object.keys(this.groupedByCourse).includes(String(Object.values(value)[1]))){
                    this.groupedByCourse[value.Course_ID] = [value]
                }else{
                    this.groupedByCourse[value.Course_ID].push(value)
                }
            },
            selectInstructor(instructor){
                
                this.$parent.selectedInstructor = instructor
                this.$parent.table = false
                this.$parent.individual = true
            },

        },
        mounted(){
            this.getInstructorDatabase()
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
        

    }
    .internal-table2 td, tr{
        width: 100%;
        border: none;
        background-color: rgba(0,0,0,0);
    }
    tr.internal-table2:hover{
        background-color:rgba(0,0,0,0);
    }

    td.internal-td {
        padding-left:0;
        padding-right:0;
    }

    .internal-table{
        pointer-events: none;
    }
    .btm-border{
        border-bottom: 0.5px solid rgba(0,0,0, 0.2);
    }
    .view-select{
        margin-top: 5em;
        margin-bottom: 2em;
        float:right;
        
    }
    .view-select select{
        border-radius: 10px;
        width:100px;
        
    }
    .eye{
        transition:0.2s;
    }
    .eye:hover{
        
        transform:translateY(-3px);
    }


</style>
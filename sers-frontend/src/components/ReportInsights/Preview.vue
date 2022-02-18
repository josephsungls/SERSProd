<template>
        <div class = "animate__animated animate__fadeIn animate__faster preview_table">
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Year</th>
                        <th scope = "col">Course</th>
                        <th scope = "col">Instructor</th>
                        <th scope = "col">Instructor Email</th>
                        <th scope = "col">Organisation</th>
                        <th scope = "col">Alumni</th>
                        <th scope = "col">Course Category</th>
                        <th scope = "col">Student</th>
                        <th scope = "col">Student Email</th>
                        <th scope = "col">Registration Status</th>
                        <th scope = "col">Completion Status</th>
                        <th scope = "col">Certification Status</th>
                    </tr>
                </thead>
                <tbody> 
                    <tr>
                        
                        <td>{{filteredData[0].Year}}</td>
                        <td>{{filteredData[0].Course_ID}} - {{filteredData[0].Course_Name}}</td>
                        <td>{{filteredData[0].Instructor_ID}} - {{filteredData[0].Instructor_Name}}</td>
                        <td>{{filteredData[0].Instructor_Email}}</td>
                        <td>{{filteredData[0].Company? filteredData[1].Company : 'NIL' }}</td>
                        <td>{{filteredData[0].Alumni? 'Yes': 'No'}}</td>
                        <td>{{filteredData[0].Category}}</td>
                        <td>{{filteredData[0].Student_ID}} - {{filteredData[0].Student_Name}}</td>
                        <td>{{filteredData[0].Student_Email}}</td>
                        <td>{{filteredData[0].Reg_Status}}</td>
                        <td>{{filteredData[0].Completion_Status}}</td>
                        <td>{{filteredData[0].Completion_Status == "completed"? "Awarded" : "NIL"}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
</template>
<script>
    export default{
        name: "Preview",
        props:{
            filteredData: Array
        }
    }
</script>
<style scoped>
    .preview_table{
        margin: auto;
        background-color: white;
        border: 1px solid var(--light-gray);
        border-radius: 10px;
        width: 100%;
        font-size:10px;
        overflow:auto;
        
        max-height: 70vh;
        height: fit-content;
		margin-bottom:5em;
    }
</style>
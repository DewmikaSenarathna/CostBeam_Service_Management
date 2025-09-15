<?php
// pages/index.php
include '../templates/header.php';
?>

<div class="intro">
    <h2>Welcome to the Civil Service System.</h2>
    <p>
        Our system is a construction project management platform designed for companies to easily track expenses, profits, and materials at site. 
        It helps management make better decisions by showing real time project performance, resource usage, and financial results. 
        With this tool, companies can reduce losses, plan ahead, and complete projects more efficiently
    </p>
<!-- Bootstrap flex + gap utility to space buttons -->
    <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="login.php" class="btn btn-primary">
            <i class="fa fa-sign-in-alt"></i> Login
        </a>
        <a href="register.php" class="btn btn-success">
            <i class="fa fa-user-plus"></i> Create Account
        </a>
    </div>

    <section class="fields" id="fields">
        <div class="line-items">
            <div class="line-item">
                <div class="line-dot"></div>
                <div class="line-content">
                    <h4><span><b>Our Mission</b></span></h4>
                    <p><em>Our mission is to empower construction companies with a comprehensive digital platform that enhances project management, financial tracking and resource control. 
                        We provide real time insights into site operations, expenses, inventory and profits, enabling companies to make informed decisions, prevent losses and optimize workflows. 
                        By centralizing all project data in a single, easy to use system, we help businesses increase efficiency, improve planning and achieve consistent profitability across all projects.</em></p><br>
                </div>
            </div>

            <div class="line-item">
                <div class="line-dot"></div>
                <div class="line-content">
                    <h4><span><b>Our Vision</b></span></h4>
                    <p><em>Our vision is to become the leading digital solution for construction project management, where companies can efficiently track finances, resources and project progress in real time. 
                        We aim to transform construction management by providing actionable insights, improving decision making and enabling organizations to complete projects on time, within budget and with maximum efficiency. 
                        Ultimately, we strive to make every construction project smarter, more transparent and more profitable.</em></p><br>
                </div>
            </div>
        </div>
    </section>


    <section class="service" id="service">
        <h2 class="heading">About Our Service</h2>
        <div class="service-container">
            <div class="service-box1">
                <div class="service-info">
                 <h4><span1>Login / Register</span1></h4><br>
                 <p><em><span2>
                    The Login/Register section provides secure access to the system. 
                    Existing users can log in using their credentials to access their projects and data safely. 
                    New users can easily create an account to start using the platform. 
                    This ensures that all project information is personalized, protected and accessible only to authorized users.</span2>
                 </em></p><br><br>
                </div>
           </div>


           <div class="service-box1">
                <div class="service-info">
                 <h4><span1>Dashboard</span1></h4><br>
                 <p><em><span2>
                    The Dashboard is the central hub of the application, offering a clear overview of all projects and activities. 
                    From here, users can add new projects, view ongoing projects, and navigate to project specific details quickly. 
                    It is designed to provide an organized and intuitive interface, allowing users to monitor overall progress and manage multiple projects efficiently.</span2>
                 </em></p><br><br>
                </div>
           </div>


           <div class="service-box1">
                <div class="service-info">
                 <h4><span1>Add New Project</span1></h4><br>
                 <p><em><span2>
                    Users can add new projects by providing essential information such as estimated cost, start date, expected completion date and client details. 
                    Once added, the project is stored securely in the system. 
                    This feature ensures accurate record keeping from the start, enabling effective planning, financial tracking and operational management throughout the project lifecycle.</span2>
                 </em></p><br><br>
                </div>
           </div>


           <div class="service-box1">
                <div class="service-info">
                 <h4><span1>View Ongoing Projects</span1></h4><br>
                 <p><em><span2>
                    The View Ongoing Projects section displays all projects currently in progress. 
                    Users can select a project to review its financial status, monitor resource allocation and track operational progress. 
                    This feature helps management make informed decisions, address issues promptly and maintain smooth workflows throughout the duration of each project.</span2>
                 </em></p><br><br>
                </div>
           </div>


           <div class="service-box1">
                <div class="service-info">
                 <h4><span1>Project Assets: Site Finance </span1></h4><br>
                 <p><em><span2>
                    Users can record all financial aspects of a project, including agreement rates, work quantities and actual expenditures. 
                    The system calculates profit or loss by comparing costs with agreed values. 
                    Reports can be generated for daily, weekly, monthly or total project duration, providing management with a clear view of financial performance and enabling proactive decision making.</span2>
                 </em></p><br><br>
                </div>
           </div>


           <div class="service-box1">
                <div class="service-info">
                 <h4><span1>Project Assets: Site Inventory </span1></h4><br>
                 <p><em><span2>
                    This section allows users to track all materials, tools, and equipment brought to the site. 
                    Each item can be recorded with its category, quantity and cost. 
                    Updates can be made anytime to monitor usage, prevent shortages and plan timely procurement for upcoming work. 
                    This ensures transparency in resource management and helps optimize site operations efficiently.</span2>
                 </em></p><br><br>
                </div>
           </div>


           <div class="service-box1">
                <div class="service-info">
                 <h4><span1>Finalize Project</span1></h4><br>
                 <p><em><span2>
                    Once a project is completed, it can be moved to the Completed Projects section. 
                    This feature preserves a historical record of all past projects, including financial results and resource usage. 
                    Management can analyze completed projects to evaluate performance, identify areas for improvement and make data driven decisions for future projects, ensuring continuous improvement and better planning.</span2>
                 </em></p><br><br>
                </div>
           </div>

        </div>
    </section>


</div>
<?php 
include '../templates/footer.php'; 
?>


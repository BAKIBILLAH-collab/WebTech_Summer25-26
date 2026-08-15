

<?php
session_start();

$name = $email = $website = $comment = $gender = "";
$errors = [];
$submitted = false;
$rememberUser = false;

if (isset($_COOKIE["remember_user"])) {
    $name = trim($_COOKIE["remember_user"]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $website = trim($_POST["website"] ?? "");
    $comment = trim($_POST["comment"] ?? "");
    $gender = trim($_POST["gender"] ?? "");
    $rememberUser = isset($_POST["remember_me"]);

    // Name
    if (empty($name)) {
        $errors["name"] = "Name is required";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $errors["name"] = "Only letters and white space allowed";
    }

    // Email
    if (empty($email)) {
        $errors["email"] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format";
    }

    // Website
    if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
        $errors["website"] = "Invalid URL";
    }

    // Comment
    if (empty($comment)) {
        $errors["comment"] = "Comment is required";
    }

    // Gender
    if (empty($gender)) {
        $errors["gender"] = "Gender is required";
    }

    if (empty($errors)) {
        $submitted = true;


        if ($rememberUser) {
            setcookie("remember_user", $name, time() + (30 * 24 * 60 * 60), "/");
        } else {
            setcookie("remember_user", "", time() - 3600, "/");
            unset($_SESSION["user"]);
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>PHP Form Validation</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .error {
            color: red;
            font-size: 14px;
        }

        .field {
            margin-bottom: 20px;
        }

        input[type=text],
        input[type=email],
        textarea {
            width: 220px;
        }

        .output {
            margin-left: 20px;
            color: black;
        }

        .field-star {
            display: none;
            color: red;
            font-weight: bold;
            margin-left: 4px;
        }
    </style>
</head>

<body>

<h2>PHP Form Validation Example</h2>

<p><span class="error">* Required field</span></p>

<form id="validationForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

    <div class="field">
        Name:
        <input type="text" name="name" id="name"
            value="<?php echo htmlspecialchars($name); ?>">
        <span class="field-star" id="nameStar">*</span>
        <span class="error" id="nameError">
            <?php echo $errors["name"] ?? ""; ?>
        </span>

        <?php if ($submitted) { ?>
            <span class="output"><?php echo htmlspecialchars($name); ?></span>
        <?php } ?>
    </div>

    <div class="field">
        Email:
        <input type="email" name="email" id="email"
            value="<?php echo htmlspecialchars($email); ?>">
        <span class="field-star" id="emailStar">*</span>
        <span class="error" id="emailError">
            <?php echo $errors["email"] ?? ""; ?>
        </span>

        <?php if ($submitted) { ?>
            <span class="output"><?php echo htmlspecialchars($email); ?></span>
        <?php } ?>
    </div>

    <div class="field">
        Website:
        <input type="text" name="website" id="website"
            value="<?php echo htmlspecialchars($website); ?>">
        <span class="field-star" id="websiteStar">*</span>
        <span class="error" id="websiteError">
            <?php echo $errors["website"] ?? ""; ?>
        </span>

        <?php if ($submitted) { ?>
            <span class="output"><?php echo htmlspecialchars($website); ?></span>
        <?php } ?>
    </div>

    <div class="field">
        Comment:
        <textarea name="comment" id="comment"><?php echo htmlspecialchars($comment); ?></textarea>
        <span class="field-star" id="commentStar">*</span>
        <span class="error" id="commentError">
            <?php echo $errors["comment"] ?? ""; ?>
        </span>

        <?php if ($submitted) { ?>
            <span class="output"><?php echo htmlspecialchars($comment); ?></span>
        <?php } ?>
    </div>

    <div class="field">
        Gender:

        <input type="radio" name="gender" value="Female"
            <?php if ($gender == "Female") echo "checked"; ?>>Female

        <input type="radio" name="gender" value="Male"
            <?php if ($gender == "Male") echo "checked"; ?>>Male

        <input type="radio" name="gender" value="Other"
            <?php if ($gender == "Other") echo "checked"; ?>>Other

        <span class="field-star" id="genderStar">*</span>
        <span class="error" id="genderError">
            <?php echo $errors["gender"] ?? ""; ?>
        </span>

        <?php if ($submitted) { ?>
            <span class="output"><?php echo htmlspecialchars($gender); ?></span>
        <?php } ?>
    </div>

    <div class="field">
        <label>
            <input type="checkbox" name="remember_me" id="remember_me" <?php echo isset($_COOKIE["remember_user"]) ? "checked" : ""; ?>> Remember Me
        </label>
    </div>

    <input type="submit" value="Login">

</form>

<script>
function validateFormFields() {
    let valid = true;

    const starIds = ["nameStar", "emailStar", "websiteStar", "commentStar", "genderStar"];
    starIds.forEach(function(id) {
        document.getElementById(id).style.display = "none";
    });

    let name = document.getElementById("name").value.trim();
    let email = document.getElementById("email").value.trim();
    let website = document.getElementById("website").value.trim();
    let comment = document.getElementById("comment").value.trim();
    let gender = document.querySelector('input[name="gender"]:checked');

    if(name=="" || !/^[A-Za-z ]+$/.test(name)){
        document.getElementById("nameStar").style.display = "inline";
        valid=false;
    }

    if(email=="" || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){
        document.getElementById("emailStar").style.display = "inline";
        valid=false;
    }

    if(website!="" && !/^(https?:\/\/)?([\w-]+\.)+[\w-]+(\/.*)?$/.test(website)){
        document.getElementById("websiteStar").style.display = "inline";
        valid=false;
    }

    if(comment==""){
        document.getElementById("commentStar").style.display = "inline";
        valid=false;
    }

    if(!gender){
        document.getElementById("genderStar").style.display = "inline";
        valid=false;
    }

    return valid;
}

document.getElementById("name").addEventListener("input", validateFormFields);
document.getElementById("email").addEventListener("input", validateFormFields);
document.getElementById("website").addEventListener("input", validateFormFields);
document.getElementById("comment").addEventListener("input", validateFormFields);
document.querySelectorAll('input[name="gender"]').forEach(function(element) {
    element.addEventListener("change", validateFormFields);
});

document.getElementById("validationForm").addEventListener("submit", function(e){
    if(!validateFormFields()){
        e.preventDefault();
    }
});
</script>

</body>
</html>


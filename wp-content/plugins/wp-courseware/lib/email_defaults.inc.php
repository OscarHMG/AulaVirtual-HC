<?php
 
/**
 * Email - Module Complete 
 */
define('EMAIL_TEMPLATE_COMPLETE_MODULE_SUBJECT', __("M&oacute;dulo {MODULE_TITLE} - Completo.", 'wp_courseware'));
define('EMAIL_TEMPLATE_COMPLETE_MODULE_BODY', __('Hola {USER_NAME}

Buen trabajo al completar el m&oacute;dulo "{MODULE_TITLE}"!

{SITE_NAME}
{SITE_URL}', 'wp_courseware'));


/**
 * Email - Course Complete 
 */
define('EMAIL_TEMPLATE_COMPLETE_COURSE_SUBJECT', __("Curso {COURSE_TITLE} - Completo", 'wp_courseware'));
define('EMAIL_TEMPLATE_COMPLETE_COURSE_BODY', __('Hola {USER_NAME}

Gran trabajo al completar el curso de capacitaci&oacute;n "{COURSE_TITLE}"! ¡Fant&aacute;stico!

{SITE_NAME}
{SITE_URL}', 'wp_courseware'));


/**
 * Email - Quiz Grade
 */
define('EMAIL_TEMPLATE_QUIZ_GRADE_SUBJECT', __('{COURSE_TITLE} - "{QUIZ_TITLE}"', 'wp_courseware'));
define('EMAIL_TEMPLATE_QUIZ_GRADE_BODY', __('Hola {USER_NAME}

Su calificaci&oacute;n para la evaluaci&oacute;n  "{QUIZ_TITLE}" es: 
{QUIZ_GRADE} 

Esto era para el examen al final de esta unidad:
{UNIT_URL}

{QUIZ_RESULT_DETAIL}

{SITE_NAME}
{SITE_URL}', 'wp_courseware')); 
		

/**
 * Email - Final Course Summary and Grade
 */
define('EMAIL_TEMPLATE_COURSE_SUMMARY_WITH_GRADE_SUBJECT', __('Su resumen final de grado para "{COURSE_TITLE}"', 'wp_courseware'));
define('EMAIL_TEMPLATE_COURSE_SUMMARY_WITH_GRADE_BODY', __( 'Hola {USER_NAME}

Felicidades por completar el curso de capacitaci&oacute;n "{COURSE_TITLE}"! ¡Fant&aacute;stico!

Su nota final es: {CUMULATIVE_GRADE} 

Aqu&iacute; est&aacute; un resumen de los resultados de la evaluaci&oacute;n:
{QUIZ_SUMMARY}

Puede descargar su certificado aqu&iacute;:
{CERTIFICATE_LINK}

Espero que hayan disfrutado el curso!

{SITE_NAME}
{SITE_URL}', 'wp_courseware')); 
 


?>
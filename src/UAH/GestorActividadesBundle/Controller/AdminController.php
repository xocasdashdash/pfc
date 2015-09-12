<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use UAH\GestorActividadesBundle\Entity\Role;
use UAH\GestorActividadesBundle\Entity\DefaultPermit;
use UAH\GestorActividadesBundle\Entity\Degree;
use UAH\GestorActividadesBundle\Entity\Category;
use Doctrine\DBAL\DBALException;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     *
     * @Security("has_role('ROLE_UAH_ADMIN')")
     */
    public function indexAction()
    {
        return new \Symfony\Component\HttpFoundation\Response("blank");
    }

    /**
     * @Route("/activities",options={"expose"=true},defaults={"_format": "html"})
     * @Security("is_granted('ROLE_UAH_ADMIN')")
     */
    public function activitiesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $filter = $request->get('filter', 'pending');

        if ($filter === 'pending') {
            $status = $em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getPending();
        } elseif ($filter === 'published') {
            $status = $em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getPublished();
        } elseif ($filter === 'closed') {
            $status = $em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getClosed();
        } elseif ($filter === 'approved') {
            $status = $em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getApproved();
        } elseif ($filter === 'draft') {
            $status = $em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getDraft();
        } elseif ($filter === 'all') {
            $status = null;
        }
        if (isset($status)) {
            $activities = $em->getRepository('UAHGestorActividadesBundle:Activity')->getAll();
        } else {
            $activities = $em->getRepository('UAHGestorActividadesBundle:Activity')->getAllByStatus($status);
        }
        $token = $this->get('form.csrf_provider')->generateCsrfToken('uah_admin');
        $cookie = new Cookie('X-CSRFToken', $token, 0, '/', null, false, false);
        $response = $this->render('UAHGestorActividadesBundle:Admin:activities.html.twig', array('activities' => $activities, 'filter' => $filter));
        $response->headers->setCookie($cookie);

        return $response;
    }

    /**
     * @Route("/activities/exportCSV/{filter}", defaults={"filter" = "all","_format": "html"} ,options={"expose"=true})
     * @Security("is_granted('ROLE_UAH_ADMIN')")
     */
    public function exportActivitiesAction($filter)
    {
        $em = $this->getDoctrine()->getManager();
        $results = $em->getRepository('UAHGestorActividadesBundle:Activity')->getExportData($filter, true);
        $response = new StreamedResponse(function () use ($results) {
            $handle = fopen('php://output', 'r+');
            $titulos = array(
                'Id', 'Nombre', 'Nombre en inglés', 'Trabajo Adicional', 'ECTS Min', 'ECTS Max', 'Libre Min', 'Libre Max', 'Inscripciones', 'ECTS Reconocidos', 'Libre Reconocidos', 'Fecha Creada', 'Fecha Solicitud Aprobación', 'Fecha Aprobación',
            );
            $titulos_printed = false;

            while (false !== ($row = $results->next())) {
                if (!$titulos_printed) {
                    fputcsv($handle, $titulos);
                    $titulos_printed = true;
                }
                fputcsv($handle, $row[0]); //[0]->toArray());
            }

            fclose($handle);
        });
        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition', 'attachment; filename="Export de datos estádisticos -filtro-'.$filter.'.csv"');

        return $response;
    }

    /**
     * @Route("/activities/approve",options={"expose"=true},defaults={"_format": "html"})
     * @Security("is_granted('ROLE_UAH_ADMIN')")
     */
    public function approveAction(Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('uah_admin', $request->headers->get('X-CSRFToken'))) {
            $em = $this->getDoctrine()->getManager();
            $status_approved = $em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getApproved();
            $status_pending = $em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getPending();
            $activities = $em->getRepository('UAHGestorActividadesBundle:Activity')->getActivitiesByID(json_decode($request->getContent()));
            $response = array();
            foreach ($activities as $activity) {
                if ($activity->getStatus() === $status_pending) {
                    $activity->setStatus($status_approved);
                    $em->persist($activity);
                    $valid_data = true;
                } else {
                    $valid_data = false;
                    break;
                }
            }
            if ($valid_data) {
                $em->flush();
                $response['type'] = 'success';
                $response['message'] = 'OK';

                return new JsonResponse($response, 200);
            } else {
                $response['type'] = 'error';
                $response['message'] = 'Ha habido un problema al realizar la solicitud';

                return new JsonResponse($response, 400);
            }
        } else {
            $response['type'] = 'error';
            $response['message'] = 'Hay un problema con el token CSRF. Prueba a recargar la página';

            return new JsonResponse($response, 400);
        }
    }

    /**
     * @Route("/activities/printpending",options={"expose"=true},defaults={"_format": "html"})
     * @Security("is_granted('ROLE_UAH_ADMIN')")
     */
    public function printpendingAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $activities = $em->getRepository('UAHGestorActividadesBundle:Activity')->getPending();

        $activities_url = array();
        foreach ($activities as $activity) {
            $url = $this->generateUrl('uah_gestoractividades_activity_index', array(
                'activity_id' => $activity['id'], ), true);
            $activities_url[] = $url;
        }
        if (!empty($activities_url)) {
            $pdf_dir = $this->container->getParameter('pdf_dir');
            $result = $this->get('knp_snappy.pdf')->generate($activities_url, $pdf_dir.'Report.pdf', array(), true);
            if (is_null($result)) {
                $content = file_get_contents($pdf_dir.'Report.pdf');
                $response = new Response();
                //set headers
                $response->headers->set('Content-Type', 'application/pdf');
                $response->headers->set('Content-Type', 'application/download');
                $response->headers->set('Content-Length', filesize($pdf_dir.'Report.pdf'));
                $response->headers->set('Content-Disposition', 'attachment;filename="Informe de actividades pendientes de aprobar.pdf"');
                $response->setContent($content);
                $response->setStatusCode(200);
            } else {
                $response = new JsonResponse('Error', 400);
            }
        } else {
            $response = new JsonResponse('Error', 400);
        }

        return $response;
    }

    /**
     * @Route("/users",defaults={"_format": "html"})
     * @Security("is_granted('ROLE_UAH_ADMIN')")
     */
    public function usersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $filter = $request->get('filter', 'ALL');

        $users_permits = $em->getRepository('UAHGestorActividadesBundle:User')->getUserPermits($filter);
        $roles = $em->getRepository('UAHGestorActividadesBundle:Role')->findAll();
        $token = $this->get('form.csrf_provider')->generateCsrfToken('uah_admin');
        $cookie = new Cookie('X-CSRFToken', $token, 0, '/', null, false, false);
        $response = $this->render('UAHGestorActividadesBundle:Admin:users.html.twig', array('users_permits' => $users_permits,
            'roles' => $roles, ));
        $response->headers->setCookie($cookie);

        return $response;
    }

    /**
     * @Route("/users/updatepermissions/{identity}/{permits}", defaults={"identity":"-1","permits":"ROLE_UAH_STUDENT","_format": "html"},options={"expose"=true})
     * @ParamConverter("role", class="UAHGestorActividadesBundle:Role",options={"mapping": {"permits": "role"}})
     * @Security("is_granted('ROLE_UAH_ADMIN')")
     * @param type $identity
     */
    public function updatePermissionsAction($identity, Role $role, Request $request)
    {
        //Leo el permiso
        //Si el permiso que voy a poner es de SUPER_ADMIN, miro que el usuario sea SUPER_ADMIN, y quito al que estaba de SUPER_ADMIN
        //Si voy a quitar permisos:
        //    - Si el usuario era admin y ya deja de serlo, miro que haya algún admin más
        //    - Si el usuario va a ser superadmin, quito al que estaba de super admin y dejo a este solo
        $response = array();

        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('uah_admin', $request->headers->get('X-CSRFToken'))) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('UAHGestorActividadesBundle:User')->findOneBy(
                    array('id_usuldap' => urldecode($identity)));
            $super_admin_role = $em->getRepository('UAHGestorActividadesBundle:Role')->getSuperAdmin();
            $admin_role = $em->getRepository('UAHGestorActividadesBundle:Role')->getAdmin();
            if ($user) {
                //Ya se ha logueado alguna vez. Actualizo ROLES
                if ($role === $super_admin_role) {
                    $securityContext = $this->container->get('security.context');
                    //Compruebo que sea el Super administrador
                    if ($securityContext->isGranted($super_admin_role->getRole())) {
                        $user->addRole($super_admin_role);
                        $super_admin_users = $super_admin_role->getUsers();
                        if (count($super_admin_users) > 1) {
                            $response['type'] = 'warning';
                            $response['message'] = 'Había más de un usuario super administrador. <br> Intente evitar esto siempre que sea posible';
                            $code = 200;
                        } else {
                            $response['type'] = 'success';
                            $response['message'] = 'Permisos actualizados';
                            $code = 200;
                        }
                        $em->persist($user);
                    } else {
                        $response['type'] = 'error';
                        $response['message'] = 'Solo un superadministrador puede crear otros superadministradores';
                        $code = 403;
                    }
                } elseif ($role === $admin_role) {
                    $user->addRole($admin_role);
                    $em->persist($user);
                    $response['type'] = 'success';
                    $response['message'] = 'Permisos actualizados';
                    $code = 200;
                }
            }
            //Actualizo los permisos. Solo dejo el último añadido
            $default_permit = $em->getRepository('UAHGestorActividadesBundle:DefaultPermit')->findOneBy(
                    array('id_usuldap' => urldecode($identity)));
            $default_permit_roles = $default_permit->getRoles();
            foreach ($default_permit_roles as $default_permit_role) {
                $default_permit->removeRole($default_permit_role);
            }
            $default_permit->addRole($role);
            $em->persist($default_permit);
            $response['type'] = 'success';
            $response['message'] = 'Permisos actualizados';
            $code = 200;

            if ($code === 200) {
                $em->flush();
            }
        } else {
            $response['type'] = 'error';
            $response['message'] = 'Hay un problema con el token CSRF. Prueba a recargar la página';
            $code = 400;
        }

        return new JsonResponse($response, $code);
    }

    /**
     * @Route("/users/deletepermissions/{identity}", defaults={"identity":"-1","_format":"html"},options={"expose"=true})
     * @Security("is_granted('ROLE_UAH_ADMIN')")
     * @param type $identity
     */
    public function deletePermissionsAction($identity, Request $request)
    {
        //No puedo borrar los permisos de un usuario que fuera SUPER_ADMINISTRADOR
        //Al quitar permisos, lo que hago es dejarlos con los permisos por defecto, ROLE_UAH_STUDENT
        $response = array();

        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('uah_admin', $request->headers->get('X-CSRFToken'))) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('UAHGestorActividadesBundle:User')->findOneBy(
                    array('id_usuldap' => urldecode($identity)));
            $super_admin_role = $em->getRepository('UAHGestorActividadesBundle:Role')->getSuperAdmin();
            $student_role = $em->getRepository('UAHGestorActividadesBundle:Role')->getStudent();
            if ($user && $user === $this->getUser()) {
                $response['type'] = 'error';
                $response['message'] = 'No te puedes borrar a ti mismo';
                $code = 400;

                return new JsonResponse($response, 400);
            }
            if ($user) {
                $user_roles = $user->getUserRoles();
                foreach ($user_roles as $user_role) {
                    if ($user_role === $super_admin_role && count($super_admin_role->getUsers()) > 1) {
                        //Hay más de un superadministrador luego puedo quitarle estos permisos
                        $user->removeRole($user_role);
                    } elseif ($user_role === $super_admin_role &&
                            count($super_admin_role->getUsers()) <= 1) {
                        //Siempre tiene que haber un administrador
                        $response['type'] = 'error';
                        $response['message'] = 'Siempre tiene que haber un super administrador. Asigna otro antes de borrarlo';
                        $code = 400;

                        return new JsonResponse($response, $code);
                    } else {
                        $user->removeRole($user_role);
                    }
                }
                $user->addRole($student_role);
                $em->persist($user);
            }
            //Borro los permisos por defecto
            $default_permit = $em->getRepository('UAHGestorActividadesBundle:DefaultPermit')->findOneBy(
                    array('id_usuldap' => urldecode($identity)));
            $em->remove($default_permit);
            /*
              $default_permit_roles = $default_permit->getRoles();
              foreach ($default_permit_roles as $default_permit_role) {
              $default_permit->removeRole($default_permit_role);
              $em->persist($default_permit);
              } */
            $em->flush();
            $response['type'] = 'success';
            $response['message'] = 'Permisos actualizados.';
            $code = 200;
        } else {
            $response['type'] = 'error';
            $response['message'] = 'Hay un problema con el token CSRF. Prueba a recargar la página';
            $code = 400;
        }

        return new JsonResponse($response, $code);
    }

    /**
     * @Route("/users/new",options={"expose"=true},defaults={"_format": "html"})
     * @Security("is_granted('ROLE_UAH_ADMIN')")
     */
    public function newUserAction(Request $request)
    {
        $response = array();

        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('uah_admin', $request->headers->get('X-CSRFToken'))) {
            $em = $this->getDoctrine()->getManager();
            $role = $em->getRepository('UAHGestorActividadesBundle:Role')->findOneBy(array(
                'role' => $request->get('uah_role'), ));
            $super_admin_role = $em->getRepository('UAHGestorActividadesBundle:Role')->getSuperAdmin();

            $uah_name = 'http://yo.rediris.es/soy/'.$request->get('uah_name').'@uah.es/';
            $securityContext = $this->container->get('security.context');
            //Compruebo que sea el Super administrador
            if ($role !== $super_admin_role) {
                $default_permit = $em->getRepository('UAHGestorActividadesBundle:DefaultPermit')->findOneBy(
                        array('id_usuldap' => $uah_name));
                if (!$default_permit) {
                    $default_permit = new DefaultPermit();
                    $default_permit->setIdUsuldap($uah_name);
                } else {
                    $default_permit_roles = $default_permit->getRoles();
                    foreach ($default_permit_roles as $default_permit_role) {
                        $default_permit->removeRole($default_permit_role);
                    }
                }
                $default_permit->addRole($role);
                $em->persist($default_permit);

                $response['type'] = 'success';
                $response['message'] = 'Usuario creado!';
                $code = 200;
            } elseif ($securityContext->isGranted($super_admin_role->getRole())) {
                $default_permit = $em->getRepository('UAHGestorActividadesBundle:DefaultPermit')->findOneBy(
                        array('id_usuldap' => $uah_name));
                if (!$default_permit) {
                    $default_permit = new DefaultPermit();
                    $default_permit->setIdUsuldap($uah_name);
                } else {
                    $default_permit_roles = $default_permit->getRoles();
                    foreach ($default_permit_roles as $default_permit_role) {
                        $default_permit->removeRole($default_permit_role);
                    }
                }
                $default_permit->addRole($role);
                $em->persist($default_permit);
                $response['type'] = 'success';
                $response['message'] = 'Usuario creado!';
                $code = 200;
            } else {
                $response['type'] = 'error';
                $response['message'] = 'Solamente un Superadmin puede crear otro';
                $code = 403;
            }
            if ($code === 200) {
                $em->flush();
            }

            return new JsonResponse($response, $code);
        }
    }

    /**
     * @Route("/degrees",defaults={"_format": "html"})
     * @Security("is_granted('ROLE_UAH_ADMIN')")
     */
    public function degreesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $filter = $request->get('filter', 'ALL');

        $degrees = $em->getRepository('UAHGestorActividadesBundle:Degree')->getDegrees($filter);
        $token = $this->get('form.csrf_provider')->generateCsrfToken('uah_admin');
        $cookie = new Cookie('X-CSRFToken', $token, 0, '/', null, false, false);
        $response = $this->render('UAHGestorActividadesBundle:Admin:degrees.html.twig', array('degrees' => $degrees));
        $response->headers->setCookie($cookie);

        return $response;
    }

    /**
     * @Route("/degrees/new.{_format}", options={"expose"=true},defaults={"_format": "json"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function newDegreeAction(Request $request)
    {
        $response = array();
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('uah_admin', $request->headers->get('X-CSRFToken'))) {
            $em = $this->getDoctrine()->getManager();
            $parameters = $request->request->all();
            if ($parameters['degree-id'] !== '') {
                //Actualizo uno que ya este
                $degree = $em->getRepository('UAHGestorActividadesBundle:Degree')
                        ->find($parameters['degree-id']);
            } else {
                //Creo uno nuevo
                $degree = new Degree();
            }
            if (is_null($degree)) {
                //No hemos encontrado esa titulación
                $response['message'] = 'No hemos encontrado esa titulación';
                $response['type'] = 'error';
                $code = 400;
            } else {
                $degree->setName($parameters['degree-name']);
                $degree->setAcademicCode($parameters['academic-code']);
                $degree->setKnowledgeArea($parameters['knowledge-area']);
                $status = $em->getRepository('UAHGestorActividadesBundle:Statusdegree')->findOneBy(array('code' => $parameters['type']));
                $degree->setStatus($status);
                $em->persist($degree);
                $em->flush();
                $response['message'] = 'Titulación creada';
                $response['type'] = 'success';
                $response['degreeId'] = $degree->getId();
                $code = 200;
            }
        } else {
            $response['message'] = 'Error con el token CSRF. Prueba a recargar la página';
            $response['type'] = 'error';
            $code = 400;
        }

        return new JsonResponse($response, $code);
    }

    /**
     * @Route("/degrees/delete/{degree_id}.{_format}", options={"expose"=true}, defaults={"_format"="json"})
     * @ParamConverter("degree", class="UAHGestorActividadesBundle:Degree",options={"id":"degree_id"})
     * @Security("is_granted('ROLE_UAH_ADMIN')")
     */
    public function deleteDegreeAction(Degree $degree, Request $request)
    {
        $response = array();
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('uah_admin', $request->headers->get('X-CSRFToken'))) {
            $em = $this->getDoctrine()->getManager();
            $status_inactivo = $em->getRepository('UAHGestorActividadesBundle:Statusdegree')->getInactive();
            $degree->setStatus($status_inactivo);
            $em->persist($degree);
            $em->flush();
            $response['message'] = 'Grado borrado';
            $response['type'] = 'success';
            $code = 200;
        } else {
            $response['message'] = 'Error con el token CSRF. Prueba a recargar la página';
            $response['type'] = 'error';
            $code = 400;
        }

        return new JsonResponse($response, $code);
    }

    /**
     * @Route("/categories")
     * @Security("is_granted('ROLE_UAH_ADMIN')")
     */
    public function categoriesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('UAHGestorActividadesBundle:Category')->getAll();
        $parent_categories = $em->getRepository('UAHGestorActividadesBundle:Category')->getParentCategories();
        $token = $this->get('form.csrf_provider')->generateCsrfToken('uah_admin');
        $cookie = new Cookie('X-CSRFToken', $token, 0, '/', null, false, false);
        $response = $this->render('UAHGestorActividadesBundle:Admin:categories.html.twig', array(
            'categories' => $categories,
            'parent_categories' => $parent_categories,
        ));
        $response->headers->setCookie($cookie);

        return $response;
    }

    /**
     * @Route("/categories/new.{_format}", options={"expose"=true}, defaults={"_format"="json"})
     * @Security("is_granted('ROLE_UAH_ADMIN')")
     */
    public function newCategoryAction(Request $request)
    {
        $response = array();
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('uah_admin', $request->headers->get('X-CSRFToken'))) {
            $em = $this->getDoctrine()->getManager();
            $parameters = $request->request->all();
            if ($parameters['category-id'] !== '') {
                //Actualizo una que ya este
                $category = $em->getRepository('UAHGestorActividadesBundle:Category')
                        ->find($parameters['category-id']);
            } else {
                //Creo una nueva
                $category = new Category();
            }
            if (is_null($category)) {
                //No hemos encontrado esa titulación
                $response['message'] = 'No hemos encontrado esa categoría';
                $response['type'] = 'error';
                $code = 400;
            } else {
                //if ($parameters['category-id'] === '') {
                $active_status = $em->getRepository('UAHGestorActividadesBundle:Statuscategory')
                        ->getActive();
                $category->setStatus($active_status);
                //}
                if (trim($parameters['category-name']) === "") {
                    $response['message'] = 'Error al crear categoría: El nombre no puede estar vacío';
                    $response['type'] = 'error';
                    $code = 400;

                    return new JsonResponse($response, $code);
                }
                $category->setName(trim($parameters['category-name']));
                //Si no elijo ningún valor, llega null y con ese valor no se encuenta ninguna categoría
                if (isset($parameters['parent-category'])) {
                    if ($parameters['parent-category'] !== $parameters['category-id']) {
                        $parent_category = $em->getRepository('UAHGestorActividadesBundle:Category')
                                ->find($parameters['parent-category']);
                    } else {
                        $response['message'] = 'Error al crear categoría. Una categoría no puede ser su propio padre';
                        $response['type'] = 'error';
                        $code = 400;

                        return new JsonResponse($response, $code);
                    }
                } else {
                    $parent_category = null;
                }
                if (!is_null($parent_category)) {
                    $category->setParentCategory($parent_category);
                    $parent_category->addChildrenCategory($category);
                    $em->persist($parent_category);
                }
                $em->persist($category);
                try {
                    $em->flush();
                } catch (DBALException $ex) {
                    $response['message'] = 'Error al crear categoría, ya existe una con esa combinación';
                    $response['type'] = 'error';
                    $code = 400;

                    return new JsonResponse($response, $code);
                }
                $response['message'] = 'Categoría creada';
                $response['type'] = 'success';
                $response['categoryId'] = $category->getId();
                $code = 200;
            }
        } else {
            $response['message'] = 'Error con el token CSRF. Prueba a recargar la página';
            $response['type'] = 'error';
            $code = 400;
        }

        return new JsonResponse($response, $code);
    }

    /**
     * @Route("/categories/delete/{category_id}.{_format}", options={"expose"=true}, defaults={"_format"="json"})
     * @Security("is_granted('ROLE_UAH_ADMIN')")
     * @ParamConverter("category", class="UAHGestorActividadesBundle:Category",options={"id":"category_id"})
     */
    public function deleteCategoryAction(Category $category, Request $request)
    {
        $response = array();
        if ($request->isXmlHttpRequest() && $request->headers->get("X-CSRFToken", null) !== null &&
                $this->get('form.csrf_provider')->isCsrfTokenValid('uah_admin', $request->headers->get('X-CSRFToken'))) {
            $em = $this->getDoctrine()->getManager();
            $status_inactivo = $em->getRepository('UAHGestorActividadesBundle:Statuscategory')->getInactive();
            $category->setStatus($status_inactivo);
            $em->persist($category);
            $em->flush();
            $response['message'] = $status_inactivo->getCode();
            $response['type'] = 'success';
            $code = 200;
        } else {
            $response['message'] = 'Error con el token CSRF. Prueba a recargar la página';
            $response['type'] = 'error';
            $code = 400;
        }

        return new JsonResponse($response, $code);
    }
}

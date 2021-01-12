<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\Type\PanelType;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\Type\ResetPasswordType;
use App\Form\Type\SearchFilterType;
use App\Form\Type\EditUserType;
use App\Form\Type\UserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/user")
 *@IsGranted("ROLE_ADMIN")
 */

class UserController extends AbstractController
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * PasswordHashSubscriber constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder=$passwordEncoder;
    }

    /**
     * @Route("/listUsers", name="user_list", methods={"GET", "POST"})
     */
    public function list( Request $request, PaginatorInterface $paginator)
    {
        $searchForm = $this->createForm(SearchFilterType::class);
        $searchForm->handleRequest($request);
        $repository = $this->getDoctrine()->getRepository(User::class);
        $items = $repository->findAll();
        $users = $paginator->paginate(
            $items,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('/Users/ListUsers.html.twig',['users'=> $users, 'search'=> $searchForm->createView()]);
    }

    /**
     * @Route("/{id}", name="user_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("user", class="App:User")
     */
    public function user($user)
    {
        return $this->render('Users/DetailsUser.html.twig', ['user'=> $user] );
    }

    /**
     * @Route("/list/{email}", name="user_by_email", methods={"GET"})
     * @ParamConverter("user", class="App:User", options={"mapping": {"email": "email"}})
     */
    public function userByEmail($user)
    {
        return $this->json($user);
    }

    /**
     * @Route("/add", name="user_add", methods={"POST","GET"})
     */
    public function add(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password= $form->get('password')->getData();
            $user->setPassword($this->passwordEncoder->encodePassword($user,$password));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('user_list');
        }
        return $this->render('Users/AddUser.html.twig', array(
            'format' => $form->createView(),
        ));
    }

    /**
     * @Route("delete/{id}", name="user_delete", methods={"GET","DELETE"})
     */
    public function delete(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('user_list');
    }

    /**
     * @Route("/edit/{id}", name="user_edit",methods={"GET","POST"})
     * @ParamConverter("user", class="App:User")
     */
    public function editUser(Request $request, $user)
    {
        $form = $this->createForm(EditUserType::class,$user);
        $form->handleRequest($request);
        $data = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(array('id' => $data->getId()));
        $originalPassword = $user->getPassword();
        if ($form->isSubmitted()) {
//            $password = $form->get('password')->getData();
            $retypePassword = $form->get('retypedPassword')['first']->getData();
            $retypePasswordS = $form->get('retypedPassword')['second']->getData();

            if (!empty($retypePassword ) ) {

                if( $retypePassword === $retypePasswordS ){
                    $user->setPassword($this->passwordEncoder->encodePassword($user,$retypePassword ));
                    $em->persist($user);
                    $em->flush();
                    return $this->redirectToRoute('user_list');
                } else {
                    $this->addFlash('danger', 'password does not match!');
                }
            } else {
                $user->setPassword($originalPassword);
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('user_list');
            }
        }
        return $this->render('Users/EditUser.html.twig', [
            'format' => $form->createView(),
        ]);
    }

    /**
     * @Route("/search", name="search_user")
     */
    public function recherche(Request $request ,UserRepository $repository) {

        $query = $request->request->get('search_filter')['search'];
        if($query){
            $users= $repository->findAllQueryBuilder($query);
        }
        return $this->render('Users/SearchUser.html.twig', ['users' => $users ]);
    }



}
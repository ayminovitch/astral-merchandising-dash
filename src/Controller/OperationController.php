<?php


namespace App\Controller;


use App\Entity\Operation;
use App\Entity\User;
use App\Service\FileUploader;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\Type\SearchFilterType;
use App\Repository\OperationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/operation")
 * @IsGranted("ROLE_ADMIN")
 */

class OperationController extends AbstractController
{
    /**
     * @Route("/listOperations", name="operation_list", requirements={"page"="\d+"}, methods={"GET"})
     */
    public function list(Request $request, PaginatorInterface $paginator)
    {
        $searchForm = $this->createForm(SearchFilterType::class);
        $searchForm->handleRequest($request);
        $repository = $this->getDoctrine()->getRepository(Operation::class);
        $items = $repository->findAll();
        $operations = $paginator->paginate(
            $items,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('Operations/ListOperation.html.twig', ['operations' => $operations, 'search'=> $searchForm->createView()]);
    }

    /**
     * @Route("/{id}", name="operation_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("operation", class="App:Operation")
     */
    public function operation($operation)
    {
        return $this->render('Operations/DetailsOperation.html.twig', ['operation' => $operation]);
    }

    /**
     * @Route("/user/{id}",name="operation_by_user", requirements={"id"="\d+"},methods={"GET"})
     */
    public function operationByUser (User $user ){
        $operation= $this->getDoctrine()->getRepository(Operation::class)->findBy(array('user' => $user));
        return $this->json($operation);
    }

    /**
     * @Route("/delete/{id}", name="operation_delete", methods={"GET", "DELETE"})
     */
    public function delete(Operation $operation)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($operation);
        $entityManager->flush();
        return $this->redirectToRoute('operation_list');
    }

    /**
     * @Route("/search", name="search_operation")
     */
    public function recherche(Request $request ,OperationRepository $repository) {

        $query = $request->request->get('search_filter')['search'];
        if($query){
            $operation= $repository->findAllQueryBuilder($query);
        }
        return $this->render('Operations/SearchOperation.html.twig', ['operations' => $operation ]);
    }

    /**
     * @Route("/listOperations/months", name="operation_list_months",methods={"GET"})
     */
    public function monthlyops(Request $request)
    {
        $conn = $this->getDoctrine()->getManager()
            ->getConnection();
        $sql = 'select MONTH(date_creation) AS month, COUNT(DISTINCT id) AS ops
                from operation
                where date_creation BETWEEN \'2020-01-01\' AND \'2020-12-31\'
                group by YEAR(date_creation), MONTH(date_creation)';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $this->json($stmt->fetchAll());
    }

    /**
     * @Route("/listOperations/gouvernorat", name="operation_list_gouvernorat", methods={"GET"})
     */
    public function percent(Request $request)
    {
        $conn = $this->getDoctrine()->getManager()
            ->getConnection();
        $sql = 'select nom , COUNT(operation.id) as nbrOperations
                from operation, station
                where (operation.station_id = station.id)
                group by nom';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $this->json($stmt->fetchAll());
    }
}
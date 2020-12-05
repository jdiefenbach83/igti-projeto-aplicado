<?php

namespace App\DataFixtures;

use App\Entity\Broker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BrokersFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $manager->persist($this->makeBroker('20017','BANCO DO BRASIL S/A','00000000000191','http://www.bb.com.br'));
        $manager->persist($this->makeBroker('810','BANCO CENTRAL DO BRASIL','00038166000105','http://www.bcb.gov.br'));
        $manager->persist($this->makeBroker('570','CAIXA ECONOMICA FEDERAL','00360305000104',null));
        $manager->persist($this->makeBroker('129','PLANNER CORRETORA DE VALORES S/A','00806535000154','http://www.planner.com.br'));
        $manager->persist($this->makeBroker('500','BANCO B3 S.A.','00997185000150',null));
        $manager->persist($this->makeBroker('1106','OURINVEST DTVM S.A.','00997804000107',null));
        $manager->persist($this->makeBroker('50054','BANCO RABOBANK INTERNATIONAL BRASIL S/A','01023570000160','http://www.rabobank.com'));
        $manager->persist($this->makeBroker('50087','BANCO COOPERATIVO SICREDI S/A','01181521000155','http://www.sicredi.com.br'));
        $manager->persist($this->makeBroker('50060','BANCO BNP PARIBAS BRASIL S/A','01522368000182','http://www.bnpparibas.com.br'));
        $manager->persist($this->makeBroker('115','H.COMMCOR DTVM LTDA','01788147000150','http://www.commcor.com.br'));
        $manager->persist($this->makeBroker('70043','TRINUS CAPITAL DTVM S.A.','02276653000123',null));
        $manager->persist($this->makeBroker('3','XP INVESTIMENTOS CCTVM S/A','02332886000104','http://www.xpi.com.br'));
        $manager->persist($this->makeBroker('308','CLEAR CORRETORA - GRUPO XP','02332886001178','http://www.clear.com.br'));
        $manager->persist($this->makeBroker('386','RICO INVESTIMENTOS - GRUPO XP','02332886001682','http://www.rico.com.vc'));
        $manager->persist($this->makeBroker('13','MERRILL LYNCH S/A CTVM','02670590000195','http://www.ml.com.br'));
        $manager->persist($this->makeBroker('88','CM CAPITAL MARKETS CCTVM LTDA','02685483000130','http://www.cmcapitalmarkets.com.br'));
        $manager->persist($this->makeBroker('50587','BANCO MORGAN STANLEY S/A','02801938000136','http://www.morganstanley.com.br'));
        $manager->persist($this->makeBroker('8','UBS BRASIL CCTVM S/A','02819125000173','http://www.ubs.com'));
        $manager->persist($this->makeBroker('5490','INFINITY CCTVM S/A','03014007000150',null));
        $manager->persist($this->makeBroker('35','FINAXIS CTVM S/A','03317692000194','http://www.finaxis.com.br'));
        $manager->persist($this->makeBroker('21','VOTORANTIM ASSET MANAGEMENT DTVM LTDA','03384738000198',null));
        $manager->persist($this->makeBroker('3142','PI DISTRIBUIDORA DE TITULOS E VALORES MOBILIARIOS S.A.','03502968000104',null));
        $manager->persist($this->makeBroker('107','TERRA INVESTIMENTOS DTVM LTDA','03751794000113','http://www.terrafuturos.com.br'));
        $manager->persist($this->makeBroker('93','NOVA FUTURA CTVM LTDA','04257795000179','http://www.futuraonline.com.br'));
        $manager->persist($this->makeBroker('40','MORGAN STANLEY CTVM S/A','04323351000194',null));
        $manager->persist($this->makeBroker('5496','GOLDMAN SACHS DO BRASIL BANCO MULTIPLO S/A','04332281000130',null));
        $manager->persist($this->makeBroker('41','ING CCT S/A','04848115000191','http://www.ing.com'));
        $manager->persist($this->makeBroker('1982','MODAL DTVM LTDA','05389174000101','http://www.modamais.com.br'));
        $manager->persist($this->makeBroker('120','GENIAL INSTITUCIONAL CCTVM S/A','05816451000115','http://www.brasilplural.com.br'));
        $manager->persist($this->makeBroker('50013','CHINA CONSTRUCTION BANK (BRASIL) BANCO MULTIPLO S.A.','07450604000189',null));
        $manager->persist($this->makeBroker('735','ICAP DO BRASIL CTVM LTDA','09105360000122','http://www.mycap.com.br'));
        $manager->persist($this->makeBroker('274','B3 S.A. – BRASIL  BOLSA  BALCÃO','09346601000125',null));
        $manager->persist($this->makeBroker('234','CODEPE CORRETORA DE VALORES E CÂMBIO S/A','09512542000118','http://www.codepe.com.br'));
        $manager->persist($this->makeBroker('238','GOLDMAN SACHS DO BRASIL CTVM SA','09605581000160','http://www.gs.com'));
        $manager->persist($this->makeBroker('262','MIRAE ASSET WEALTH MANAGEMENT (BRASIL) CCTVM LTDA.','12392983000138',null));
        $manager->persist($this->makeBroker('3701','ORAMA DTVM S A','13293225000125',null));
        $manager->persist($this->makeBroker('1445','VIC DTVM S/A','14388516000160',null));
        $manager->persist($this->makeBroker('50037','BANCO BOCOM BBM SA','15114366000169','http://www.bancobbm.com.br'));
        $manager->persist($this->makeBroker('18','BOCOM BBM CCVM S/A','15213150000150','http://www.bancobbm.com.br'));
        $manager->persist($this->makeBroker('106','MERCANTIL DO BRASIL C. S/A CTVM','16683062000185',null));
        $manager->persist($this->makeBroker('252','BANCO ITAU BBA S/A','17298092000130','http://www.itaubba.com.br'));
        $manager->persist($this->makeBroker('226','AMARIL FRANKLIN CTV LTDA','17312661000155','http://www.amarilfranklin.com.br'));
        $manager->persist($this->makeBroker('187','SITA SOCIEDADE CCVM S/A','17315359000150','http://www.sita.com.br'));
        $manager->persist($this->makeBroker('191','SENSO C.C.V.M. S/A','17352220000187','http://www.sensoccvm.com.br'));
        $manager->persist($this->makeBroker('1099','INTER DTVM LTDA','18945670000146',null));
        $manager->persist($this->makeBroker('820','BB BANCO DE INVESTIMENTO S/A','24933830000130',null));
        $manager->persist($this->makeBroker('181','MUNDINVEST S/A - CCVM','25674235000190','http://www.mundinvest.com.br'));
        $manager->persist($this->makeBroker('173','GENIAL INVESTIMENTOS CORRETORA DE VALORES MOBILIÁRIOS S.A.','27652684000162','http://www.genialinvestimentos.com.br'));
        $manager->persist($this->makeBroker('1423','BANCO BRJ S/A','27937333000106',null));
        $manager->persist($this->makeBroker('174','ELITE CCVM LTDA','28048783000100','http://www.eliteccvm.com.br'));
        $manager->persist($this->makeBroker('3112','BANESTES DTVM SA','28156057000101',null));
        $manager->persist($this->makeBroker('29','UNILETRA CCTVM LTDA.','28156214000170',null));
        $manager->persist($this->makeBroker('4015','BS2 DTVM S.A.','28650236000192',null));
        $manager->persist($this->makeBroker('50893','SCOTIABANK BRASIL S.A. BANCO MULTIPLO','29030467000166',null));
        $manager->persist($this->makeBroker('4090','TORO CTVM LTDA','29162769000198',null));
        $manager->persist($this->makeBroker('50034','BANCO BTG PACTUAL S/A','30306294000145','http://www.btgpactual.com'));
        $manager->persist($this->makeBroker('683','BANCO MODAL S/A','30723886000162','http://www.modal.com.br'));
        $manager->persist($this->makeBroker('50889','BANCO MODAL S/A','30723886000162','http://www.modal.com.br'));
        $manager->persist($this->makeBroker('713','BB GESTAO DE RECURSOS DTVM S.A.','30822936000169','http://www.bb.com.br'));
        $manager->persist($this->makeBroker('1618','IDEAL CTVM S.A.','31749596000150',null));
        $manager->persist($this->makeBroker('50577','BANCO BVA S/A','32254138000103','http://www.bancobva.com.br'));
        $manager->persist($this->makeBroker('6003','C6 CORRETORA DE TITULOS E VALORES MOBILIARIOS LTDA','32345784000186',null));
        $manager->persist($this->makeBroker('16','J.P. MORGAN CCVM S/A','32588139000194','http://www.jpmorgan.com'));
        $manager->persist($this->makeBroker('50069','BANCO J.P.MORGAN S/A','33172537000198','http://www.jpmorgan.com'));
        $manager->persist($this->makeBroker('50935','BANCO XP S.A','33264668000103',null));
        $manager->persist($this->makeBroker('1116','BANCO CITIBANK S/A','33479023000180',null));
        $manager->persist($this->makeBroker('247','BANCO CITIBANK S/A','33479023000180',null));
        $manager->persist($this->makeBroker('77','CITIGROUP GLOBAL MARKETS BRASIL CCTVM S/A','33709114000164','http://www.citicorretora.com.br'));
        $manager->persist($this->makeBroker('147','ATIVA INVESTIMENTOS S/A CTCV','33775974000104','http://www.ativactv.com.br'));
        $manager->persist($this->makeBroker('711','DILLON S/A DTVM','33851064000155',null));
        $manager->persist($this->makeBroker('122','BGC LIQUIDEZ DTVM LTDA','33862244000132','http://www.bgcpartners.com'));
        $manager->persist($this->makeBroker('298','CITIBANK DTVM S/A','33868597000140','http://www.citibank.com.br'));
        $manager->persist($this->makeBroker('227','GRADUAL CORRET DE CAMBIO TIT E VALS MOB SA','33918160000173','http://www.gradualinvestimentos.com.br'));
        $manager->persist($this->makeBroker('55246','BANCO MAXIMA S/A','33923798000100',null));
        $manager->persist($this->makeBroker('37','UM INVESTIMENTOS S/A CTVM','33968066000129','http://www.uminvestimentos.com.br'));
        $manager->persist($this->makeBroker('50053','BANCO DE INVEST. CREDIT SUISSE (BRASIL) S.A.','33987793000133','http://www.credit-suisse.com'));
        $manager->persist($this->makeBroker('1855','VITREO DTVM S.A.','34711571000156',null));
        $manager->persist($this->makeBroker('150','PROSPER S/A CVC','36178887000150','http://www.prospercorretora.com.br'));
        $manager->persist($this->makeBroker('3762','RJI CTVM LTDA','42066258000130',null));
        $manager->persist($this->makeBroker('45','CREDIT SUISSE (BRASIL) S.A. CTVM','42584318000107','http://www.csfb.com.br'));
        $manager->persist($this->makeBroker('63','NOVINVEST CVM LTDA','43060029000171','http://www.novinvest.com.br'));
        $manager->persist($this->makeBroker('85','BTG PACTUAL CTVM S/A','43815158000122','http://www.ubs.com/brasil'));
        $manager->persist($this->makeBroker('4002','BANCO ANDBANK (BRASIL) S.A.','48795256000169',null));
        $manager->persist($this->makeBroker('50065','ING BANK N.V.','49336860000190','http://www.ing.com.br'));
        $manager->persist($this->makeBroker('110','SLW CVC LTDA','50657675000186','http://www.slw.com.br'));
        $manager->persist($this->makeBroker('27','SANTANDER CCVM S/A','51014223000149','http://www.santandercorretora.com.br'));
        $manager->persist($this->makeBroker('23','NECTON INVESTIMENTOS S.A. CVMC','52904364000108','http://www.concordia.com.br'));
        $manager->persist($this->makeBroker('301','BEXS CORRETORA DE CAMBIO S/A','52937216000181','http://www.didierlevy.com.br'));
        $manager->persist($this->makeBroker('990','SSM - SISTEMA DE SIMULACAO DE MARGENS','54641030000378',null));
        $manager->persist($this->makeBroker('50070','BANCO SAFRA S/A','58160789000128','http://www.safra.com.br'));
        $manager->persist($this->makeBroker('2197','BANCO FIBRA S/A','58616418000108','http://www.bancofibra.com.br'));
        $manager->persist($this->makeBroker('55245','BANCO PAN S.A.','59285411000113',null));
        $manager->persist($this->makeBroker('1722','BANCO VOTORANTIM S/A','59588111000103','http://www.bancovotorantim.com.br'));
        $manager->persist($this->makeBroker('50028','BANCO MUFG BRASIL S.A.','60498557000126','http://www.br.bk.mufg.jp'));
        $manager->persist($this->makeBroker('3605','ITAU UNIBANCO S/A','60701190000104','http://www.itau.com.br'));
        $manager->persist($this->makeBroker('3594','BANCO BRADESCO S/A','60746948000112','http://www.bradesco.com.br'));
        $manager->persist($this->makeBroker('50030','BANCO ALFA DE INVESTIMENTO S/A','60770336000165','http://www.bancoalfa.com.br'));
        $manager->persist($this->makeBroker('59','SAFRA CORRETORA DE VALORES E CAMBIO LTDA','60783503000102','http://www.safra.com.br'));
        $manager->persist($this->makeBroker('1093','BANCO SOFISA S/A','60889128000180','http://www.sofisa.com.br'));
        $manager->persist($this->makeBroker('50047','BANCO INDUSVAL S/A','61024352000171','http://www.indusval.com.br'));
        $manager->persist($this->makeBroker('50026','BANCO MIZUHO DO BRASIL S/A','61088183000133','http://www.mizuhobank.com/brazil/pt'));
        $manager->persist($this->makeBroker('114','ITAU CV S/A','61194353000164','http://www.itautrade.com.br'));
        $manager->persist($this->makeBroker('50059','BANCO SOCIETE GENERALE BRASIL S/A','61533584000155','http://www.sgbrasil.com.br'));
        $manager->persist($this->makeBroker('1','MAGLIANO S/A CCVM','61723847000199','http://www.magliano.com.br'));
        $manager->persist($this->makeBroker('127','TULLETT PREBON BRASIL CVC LTDA.','61747085000160','http://www.tullettprebon.com.br/'));
        $manager->persist($this->makeBroker('72','BRADESCO S/A CTVM','61855045000132',null));
        $manager->persist($this->makeBroker('33','LEROSA S.A. CORRETORA DE VALORES E CAMBIO','61973863000130','http://www.lerosa.com.br'));
        $manager->persist($this->makeBroker('50912','BANK OF AMERICA MERRILL LYNCH BANCO MULTIPLO','62073200000121','http://www.mi.com'));
        $manager->persist($this->makeBroker('1130','INTL FCSTONE DTVM LTDA','62090873000190',null));
        $manager->persist($this->makeBroker('50506','BANCO PINE S/A','62144175000120','http://www.bancopine.com.br'));
        $manager->persist($this->makeBroker('90','EASYNVEST - TITULO CV S/A','62169875000179','http://www.titulo.com.br'));
        $manager->persist($this->makeBroker('4','ALFA CCVM S/A','62178421000164','http://www.alfanet.com.br'));
        $manager->persist($this->makeBroker('359','BANCO DAYCOVAL S/A','62232889000190','http://www.daycoval.com.br'));
        $manager->persist($this->makeBroker('133','DIBRAN DTVM LTDA','62280490000184','http://wwww.dibran.com.br'));
        $manager->persist($this->makeBroker('58','SINGULARE CORRETORA DE TÍTULOS E VALORES MOBILIÁRIOS S.A.','62285390000140',null));
        $manager->persist($this->makeBroker('92','RENASCENCA DTVM LTDA','62287735000103','http://www.dtvm.com.br'));
        $manager->persist($this->makeBroker('2570','SANTANDER CACEIS BRASIL DTVM S.A.','62318407000119',null));
        $manager->persist($this->makeBroker('50056','DEUTSCHE BANK S/A - BANCO ALEMAO','62331228000111','http://www.db.com/brazil/'));
        $manager->persist($this->makeBroker('131','FATOR S/A - CORRETORA DE VALORES','63062749000183','http://www.fator.com.br'));
        $manager->persist($this->makeBroker('15','GUIDE INVESTIMENTOS SA CORRETORA DE VALORES','65913436000117','http://www.guide.com.br'));
        $manager->persist($this->makeBroker('2640','LLA DTVM LTDA','67600379000141',null));
        $manager->persist($this->makeBroker('177','SOLIDUS S/A CCVM','68757681000170','http://www.solidus.com.br'));
        $manager->persist($this->makeBroker('3371','RIO BRAVO INVESTIMENTOS SA DTVM','72600026000181',null));
        $manager->persist($this->makeBroker('39','AGORA CTVM S/A','74014747000135','http://www.agorainvest.com.br'));
        $manager->persist($this->makeBroker('2205','BANCO CREDIT AGRICOLE BRASIL S.A.','75647891000171','http://www.calyon.com'));
        $manager->persist($this->makeBroker('442','BANCO OURINVEST S/A','78632767000120','http://www.ourinvest.com.br'));
        $manager->persist($this->makeBroker('1089','RB CAPITAL INVESTIMENTOS DTVM LTDA','89960090000176',null));
        $manager->persist($this->makeBroker('50898','BANCO SANTANDER (BRASIL) S.A.','90400888000142','http://www.santanderbanespa.com.br'));
        $manager->persist($this->makeBroker('186','CORRETORA GERAL DE VALORES E CAMBIO LTDA','92858380000118','http://www.geralinvestimentos.com.br'));
        $manager->persist($this->makeBroker('190','WARREN CVMC LTDA','92875780000131','http://www.warren.com.br'));
        $manager->persist($this->makeBroker('2379','ORLA DTVM S.A.','92904564000177',null));
        $manager->persist($this->makeBroker('172','BANRISUL S/A CORRETORA DE VAL MOB E CAMBIO','93026847000126','http://www.banrisulcorretora.com.br'));

        $manager->flush();
    }

    private function makeBroker(string $code, string $name, string $cnpj, ?string $site): Broker
    {
        return (new Broker())
            ->setCode($code)
            ->setName($name)
            ->setCnpj($cnpj)
            ->setSite($site);
    }
}
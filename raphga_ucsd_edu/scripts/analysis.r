# code for pathway analysis (KEGG based)
# input:
# output:
# required reference: kegg database
# parameter setting :
#
#            B: input genes, length(mgene.id) for all differential genes
#               gene_in_pathwaydatabase for differential genes in mouse KEGG pathways

#***************************************************************************
#             THIS CONTROLS WHERE WE GET OUR INPUTS FROM
# If for_web is TRUE, the inputs are expected to come from the 
# command line, something like:
# Rscript analysis.r var1 var2 var3 var4 var5 var6 var7 var8
# if for_web is FALSE, the inputs are manually set inside this file
#***************************************************************************
for_web <- TRUE


############################   pathway analysis #############################

#********************* set paramters ************************************
if (for_web) {
  setwd("/var/www/scripts")
  args <- commandArgs(TRUE)

  species <- args[1]
  methodx <- args[2]
  inputx <- args[3]
  input_file <- args[4]
  pathway_output <- args[5]
  hub_output_full <- args[6]
  hub_output_short <- args[7]
  gene_nums_output <- args[8]
  pathway_output_full <- args[9]
  #data <- c(species, methodx, inputx, input_file, pathway_output, hub_output_full, hub_output_short, gene_nums_output)
  #print(data) 
} else {
  setwd("C:\\test\\gan_KEGG_database")

  #species: hsa  mmu  dme
  species <- "dme"

  #methodx: KEGG-based genome-based
  methodx <-"KEGG-based"

  #inputx: NCBI_id, symbol
  inputx <- "symbol"

  #************** Define filenames here ***********************
  input_file <- "2h2p_p01_sym.txt"
  pathway_output_full <- "pathway_result.csv"
  pathway_output <- "pathway_result2.csv"
  hub_output_full <- "hgene_result.csv"
  hub_output_short <- "hgene_result2.csv"
}

#************************* read input data ********************************
  mgene <- read.delim (input_file, header=FALSE)

#********* read pathway database and set gene background *****************
if(species=="mmu"){
  pathways <- as.matrix(read.delim("mmu.txt", header=TRUE))
  genemap <- as.matrix(read.delim("mmu_gene.txt", header=TRUE))
  if(methodx=="genome-based"){
  	totalgene<-23000
  }else{
        totalgene<-7572
  }
}else if(species=="hsa"){
  pathways <- as.matrix(read.delim("hsa.txt", header=TRUE))
  genemap <- as.matrix(read.delim("hsa_gene.txt", header=TRUE))
  if(methodx=="genome-based"){
  	totalgene<-21000
  }else{
        totalgene<-6788
  }
}else if(species=="dme"){
  pathways <- as.matrix(read.delim("dme.txt", header=TRUE))
  genemap <- as.matrix(read.delim("dme_gene.txt", header=TRUE))
  if(methodx=="genome-based"){
  	totalgene<-17000
  }else{
        totalgene<-2711
  }
}else{
}
pathways.id <- pathways[,1]
pathways.genes <- pathways[,2]
pathways.genecount <- pathways[,3]


#************read pathway summary******************************************
msummary <- read.delim("summary.txt", header=TRUE)
summary.sp <- msummary[["species"]]
summary.id <- msummary [["pathway_id"]]
summary.name <- msummary[["pathway_name"]]


#************************* convert input data ******************************
geneid <- as.integer(genemap[,1])
genesymbol <- genemap[,2]
if(inputx=="NCBI_id"){
    mgene.id <- as.integer(mgene[,1])
    mgene.sym <- array("", dim=length(mgene.id))
    for (i in 1:length(mgene.id)){
    	tempindex <-match(mgene.id[i], geneid, nomatch=-1)
        if (tempindex==-1){
	}else{
		mgene.sym[i]<-genesymbol[tempindex]
	}
    }
}else if(inputx=="symbol"){
    mgene.sym <- as.character(mgene[,1])
    mgene.id <- array(0, dim=length(mgene[,1]))
    tempn <- 0
    for (i in 1:length(mgene.sym)){
	tempsym<-as.character(mgene.sym[i])
	tempindex <-match(toupper(tempsym), toupper(genesymbol), nomatch=-1)
        if (tempindex==-1){
	}else{
		tempn <- tempn+1
		mgene.id[tempn]<-geneid[tempindex]
                mgene.sym[tempn]<-tempsym
	}
    }
    mgene.id <- mgene.id[1:tempn]
    mgene.sym <-mgene.sym[1:tempn]
}else{

}

# create a matrix to count genes in pathways
gene_in_pathwaydatabase <- intersect(mgene.id, as.integer(pathways.genes))

#define B, and report as #genes in KEGG pathways: B
if(methodx=="genome_base"){
  B<-length(mgene.id)
}else{
  B <- length(gene_in_pathwaydatabase)
}

#************** here report how many genes identified ******************
# #input genes: length(mgene[,1])   #identified genes: length(mgene.id)
# #gene in KEGG: B
if (for_web){
  # We can't pass variables from R to PHP, so we need to save any
  # results we want into a file that PHP we then open/retrieve the
  # data, updated 05-25-2015 for correct display
  if(length(mgene.id)<2){
    data_for_php <- c(length(mgene[,1]), 0, length(gene_in_pathwaydatabase))  
  }else{
    data_for_php <- c(length(mgene[,1]), length(mgene.id), length(gene_in_pathwaydatabase))
  }

  # write out the data
  write(data_for_php, gene_nums_output, sep = ",")
}


# set the output pathway list for results
temp_path <- as.matrix(unique(pathways.id[duplicated(pathways.id)]))
result.pathway <- as.matrix(array("", dim=c(length(temp_path),6)))
result.pathway[,1] <- temp_path

# map gene list into the pathway database
for (i in 1:length(result.pathway[,2])){
    # get the ith pathway id
    ipath <- result.pathway[i,1]
    # get the corresponding index of the path, to get the path description
    temp_index <- which(summary.id==ipath)[1]
    # get the corresponding path description
    result.pathway[i,2] <- as.character(summary.name[temp_index])
    # get the gene list in this pathway
    iset <- subset(pathways, pathways[,1]==ipath)
    # get the common genes between ipath's genes and input genes
    imapped <- intersect(mgene.id, as.integer(iset[,2]))
    # count the mapped input genes in ipath
    result.pathway[i,3] <- length(imapped)
    # count the total genes in ipath
    result.pathway[i,4] <- length(iset[,1])
    # list the mapped genes for ipath
    ## this code will create a csv with separated gene list
    #result.pathway[i,6] <- paste(imapped, sep="", collapse=",")
    ## this code will create a csv with integrated gene list
    result.pathway[i,6] <- paste(imapped, sep="", collapse=" ")

    # calculate p value , fisher's exact test
    A<-length(imapped)
    N<-length(iset[,1])
    M<-totalgene
    v<-rbind(c(A, B-A), c(N-A, M-N-B+A))
    p<-fisher.test(v, alternative="greater")
    # add p value for ipath
    result.pathway[i,5] <- as.character(p[1])
}
# define the column names
if (for_web){
  colnames(result.pathway)<- c("KEGG pathway id", "KEGG pathway name", "Number of mapped genes in this pathway", "Total number of genes in this pathway", "P value","Mapped gene id list")  
} else {
  colnames(result.pathway)<- c("pathway_id", "pathway", "# mapped gene", "#total genes", "p value","gene_id list")
}
# order the pathways by p values
moutput<-result.pathway[order(as.numeric(result.pathway[,5]), decreasing=FALSE),]
## note:  write.table, will convert gene_list string into a number
##        write.csv, will use ',' as the separator in the value
##       (can't be changed), so avoid ',' in the writing data
write.csv(moutput, pathway_output_full, row.names = FALSE, quote=FALSE)

# read gene count file
genecount <- as.matrix(read.delim("genecounts.txt", header=TRUE))
if (species=="mmu"){
  mgenecount <- as.matrix(genecount[1:7572,1:2])
}else if(species=="hsa"){
  mgenecount <- as.matrix(genecount[1:6788,3:4])
}else if(species=="dme"){
  mgenecount <- as.matrix(genecount[1:2711,5:6])
}else{
  mgenecount <- 0
}
mgenecount[,1] <- gsub(" ","",(mgenecount[,1]))

# get the total of pathways in KEGG database
hgeneM <- length(temp_path)

# ********************* set p value threshold ******************************
pthreshold <- 0.05
# get the pathways with p<threshold
pset <- subset(moutput, as.numeric(moutput[,5])<pthreshold)

write.csv(pset, pathway_output, row.names = FALSE, quote=FALSE)

hgeneB <- length(pset[,1])
# calculate the number of mapped genes in all significant pathways
nmapped <- apply(as.matrix(as.numeric(pset[,3])),2,sum)
# create a varilable to store the total list of genes mapped, including duplicates
gmapped <- as.matrix(array("", dim=nmapped))
tempindex <- 0
for (i in 1:length(pset[,1])){
  igenelist <- unlist(strsplit(pset[i,6]," "))
  for (j in  1: length(igenelist)){
    tempindex <- tempindex+1
    gmapped[tempindex]<- igenelist[j]
  }
}

# get the list of input genes mapped into KEGG pathways
genestat <- as.matrix(array("", dim=c(length(gene_in_pathwaydatabase), 5)))
genestat[,1] <- gene_in_pathwaydatabase

for (i in 1:length(genestat[,1])){
  igene <- genestat[i,1]
  genestat[i,2] <- mgene.sym[match(igene,mgene.id)]
  # get the total pathway number containing igene
  tnum <- mgenecount[which(mgenecount[,1]==igene),2]
  hgeneN <- as.numeric(tnum)
  genestat[i,3]<- tnum
  # get the total differential pathway number containing igene
  hgeneA <- length(which(gmapped==igene))
  genestat[i,4]<- hgeneA
  # calculate fisher test's p value
  hgeneV <- rbind(c(hgeneA, hgeneN-hgeneA), c(hgeneB-hgeneA, hgeneM-hgeneN-hgeneB+hgeneA))
  hgeneP <- fisher.test(hgeneV, 2, 2, alternative="greater")
  genestat[i,5] <- as.character(hgeneP[1])
}

# define the column names
if (for_web){
  colnames(genestat)<- c("Gene id", "Gene Name", "#total mapped KEGG pathways", "#mapped significant pathways", "P value")
} else {
  colnames(genestat)<- c("gene_id", "gene_name", "#KEGG pathways", "#significant pathways", "p value")
}

# order the pathways by p values
poutput<-genestat[order(as.numeric(genestat[,5]), decreasing=FALSE),]

## note:  write.table, will convert gene_list string into a number
##        write.csv, will use ',' as the separator in the value
write.csv(poutput, hub_output_full, row.names = FALSE, quote=FALSE)

# filter out those genes mapped <3 significant pathways
poutput2 <- subset(poutput, as.numeric(poutput[,4])>1)
# 05-25-2015, filter out p>0.05
poutput2 <- subset(poutput, as.numeric(poutput[,5])<0.05)
write.csv(poutput2, hub_output_short, row.names = FALSE, quote=FALSE)
